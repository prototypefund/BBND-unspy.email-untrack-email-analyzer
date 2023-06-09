<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\Log\AnalyzerLogger;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\Result\FullResult;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\Result\FullResultWrapper;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\Result\ResultDetails;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\Result\Url\UrlItemInfo;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\Result\Url\UrlItemInfoBagBuilder;
use Geeks4change\UntrackEmailAnalyzer\Globals;
use Geeks4change\UntrackEmailAnalyzer\Matcher\MatcherManager;
use Symfony\Component\DomCrawler\Crawler;
use ZBateson\MailMimeParser\MailMimeParser;

final class Analyzer {

  protected \DateTime $dateTime;

  public function __construct(
    protected AnalyzerLogBuilderInterface $logger = new AnalyzerLogger(),
    protected HeaderItemExtractor         $headerItemExtractor = new HeaderItemExtractor(),
    protected UrlExtractor                $urlExtractor = new UrlExtractor(),
    protected UrlRedirectCrawler          $redirectCrawler = new UrlRedirectCrawler(),
    protected MatcherManager              $matcherManager = new MatcherManager(),
    protected AnalyticsMatcher            $analyticsMatcher = new AnalyticsMatcher(),
    protected CnameDumper                 $cnameDumper = new CnameDumper(),
  ) {
    $this->dateTime = (new \DateTime())
      // Use zulu time to not get test issues.
      ->setTimezone(new \DateTimeZone('utc'));
  }

  public function setUnitTestMode(): self {
    $this->redirectCrawler->setRedirectResolver(NULL);
    $this->dateTime = (new \DateTime())->setTimestamp(0)
      // Use zulu time to not get test issues.
      ->setTimezone(new \DateTimeZone('utc'));
    return $this;
  }

  public function analyze(string $rawMessage, bool $throwExceptions = false): FullResultWrapper {
    $timestamp = $this->dateTime->format('c'); // ISO8601
    $this->logger->info("[$timestamp] Start");

    try {
      // Check DKIM.
      $dkimResult = (new DKIMSignatureValidator())->validateDKIMSignature($rawMessage);

      // Parse and find patterns.
      $mailParser = new MailMimeParser();
      $message = $mailParser->parse($rawMessage, FALSE);
      // @todo Consider reporting unusual Mime parts, like more than one text/html part.

      $headerItemBag = $this->headerItemExtractor->extract($message);
      $headerItemInfoBag = $this->matcherManager->matchHeaders($headerItemBag);

      // Analyze body html.
      $html = $message->getHtmlContent();
      $urlItemBag = $this->urlExtractor->extract($html);

      // Now...
      // - match urls for Unsubscribe
      // - match urls for UserTracking
      // - match urls for Analytics
      $urlItemInfoBag0 = UrlItemInfoBagBuilder::fromUrlItemBag($urlItemBag)->freeze();
      $urlItemInfoBag1 = $this->matcherManager->matchUrls($urlItemInfoBag0);

      // Remove known technical urls from redirect checking.
      $allowCrawling = fn(UrlItemInfo $urlItemInfo) => $urlItemInfo->getNoRedirectCheckProviderIds();
      $urlItemInfoBag2 = $this->redirectCrawler->crawlRedirects($urlItemInfoBag1, $allowCrawling);
      $urlItemInfoBag3 = $this->analyticsMatcher->matchAnalyticsUrls($urlItemInfoBag2);

      // Dump crawled CNames.
      $cnameChainList = $this->cnameDumper->dumpCnames();

      $resultDetails = new ResultDetails(
        $dkimResult,
        $headerItemInfoBag,
        $urlItemInfoBag3,
        $cnameChainList,
      );

      $resultVerdict = (new ResultVerdictExtractor)
        ->extractResultVerdict($resultDetails);

      $listInfo = (new ListInfoExtractor())->extract($message);
      $messageInfo = (new MessageInfoExtractor())->extract($message);

      $timestamp = $this->dateTime->format('c'); // ISO8601
      $this->logger->info("[$timestamp] Finished");

      $analyzerResult = new FullResultWrapper($this->logger->freeze(),
        new FullResult($listInfo,
          $messageInfo,
          $resultVerdict,
          $resultDetails)
      );

    } catch (\Throwable $e) {
      if ($throwExceptions) {
        throw new \RuntimeException('Rethrow', 0, $e);
      }
      $this->logger->critical("Exception: {$e->getMessage()}", ['trace' => $e->getTraceAsString()]);
      return new FullResultWrapper($this->logger->freeze(), NULL);
    }

    Globals::deleteAll();
    return $analyzerResult;
  }


}

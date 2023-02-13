<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer2;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\AnalyzerLog\AnalyzerLogger;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\DKIMSignatureValidator;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\ListInfoExtractor;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\MessageInfoExtractor;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\ResultSummaryExtractor;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\ResultVerdictExtractor;
use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\UrlItemInfo;
use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\UrlItemInfoBagBuilder;
use Geeks4change\UntrackEmailAnalyzer\Analyzer2\ResultDetails\FullResult;
use Geeks4change\UntrackEmailAnalyzer\Analyzer2\ResultDetails\FullResultWrapper;
use Geeks4change\UntrackEmailAnalyzer\Analyzer2\ResultDetails\ResultDetails;
use Geeks4change\UntrackEmailAnalyzer\Globals;
use Geeks4change\UntrackEmailAnalyzer\Matcher2\MatcherManager;
use Symfony\Component\DomCrawler\Crawler;
use ZBateson\MailMimeParser\MailMimeParser;

final class Analyzer2 {


  public function __construct(
    protected AnalyzerLogBuilderInterface $logger = new AnalyzerLogger(),
    protected HeaderItemExtractor         $headerItemExtractor = new HeaderItemExtractor(),
    protected UrlExtractor                $urlExtractor = new UrlExtractor(),
    protected UrlRedirectCrawler          $redirectCrawler = new UrlRedirectCrawler(),
    protected MatcherManager              $matcherManager = new MatcherManager(),
    protected AnalyticsMatcher            $analyticsMatcher = new AnalyticsMatcher(),
    protected CnameDumper                 $cnameDumper = new CnameDumper(),
  ) {}

  public function analyze(string $rawMessage, bool $catchAndLogExceptions = TRUE): FullResultWrapper {
    $timestamp = (new \DateTime())->format('c'); // ISO8601
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
      // Do we need all the bells and whistles of HtmlPageCrawler and underlying
      // DomCrawler? Not really, but seems to add some namespace and encoding
      // safeguards that can not be wrong.
      $crawler = new Crawler($html);
      $urlItemBag = $this->urlExtractor->extract($crawler);

      // Crawl CNames.
      $cnameChainList = $this->cnameDumper->dumpCnames();

      // Now...
      // - match urls for Unsubscribe
      // - match urls for UserTracking
      // - match urls for Analytics
      $urlItemInfoBag0 = UrlItemInfoBagBuilder::fromUrlItemBag($urlItemBag)->freeze();
      $urlItemInfoBag1 = $this->matcherManager->matchTechnicalUrls($urlItemInfoBag0);
      $urlItemInfoBag2 = $this->matcherManager->matchUserTrackingUrls($urlItemInfoBag1);

      // Remove known technical urls from redirect checking.
      $allowCrawling = fn(UrlItemInfo $urlItemInfo) => !$urlItemInfo->technicalUrlMatchesById;
      $urlItemInfoBag3 = $this->redirectCrawler->crawlRedirects($urlItemInfoBag2, $allowCrawling);
      $urlItemInfoBag4 = $this->analyticsMatcher->matchAnalyticsUrls($urlItemInfoBag3);

      $resultDetails = new ResultDetails(
        $dkimResult,
        $headerItemInfoBag,
        $urlItemInfoBag4,
        $cnameChainList,
      );
      dump($urlItemInfoBag4->anonymize());
      exit;

      $resultSummary = (new ResultSummaryExtractor)
        ->extractResultSummary($resultDetails);
      $resultVerdict = (new ResultVerdictExtractor)
        ->extractResultVerdict($resultSummary);

      $listInfo = (new ListInfoExtractor())->extract($message);
      $messageInfo = (new MessageInfoExtractor())->extract($message);
      $analyzerResult = new FullResultWrapper($this->logger->freeze(), new FullResult($listInfo, $messageInfo, $resultVerdict, $resultSummary, $resultDetails));
    } catch (\Throwable $e) {
      if (!$catchAndLogExceptions) {
        throw new \RuntimeException('Rethrow', 0, $e);
      }
      $this->logger->emergency("Exception: {$e->getMessage()}", ['trace' => $e->getTraceAsString()]);
      return new FullResultWrapper($this->logger->freeze(), NULL);
    }

    $timestamp = (new \DateTime())->format('c'); // ISO8601
    $this->logger->info("[$timestamp] Finished");

    Globals::deleteAll();
    return $analyzerResult;
  }


}

<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer2;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyticsDetector;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\AnalyzerLog\AnalyzerLogger;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\FullResult;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\FullResultWrapper;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\ResultDetails;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\TypedUrlList;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\DKIMSignatureValidator;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\DomainAliasesResultFetcher;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\ListInfoExtractor;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\MessageInfoExtractor;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\NewsletterServicesMatcher\UrlsMatcher\AllServicesLinkAndImageUrlListMatcher;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\ResultSummaryExtractor;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\ResultVerdictExtractor;
use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\UrlItemInfo;
use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\UrlItemInfoBagBuilder;
use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\UrlItemMatchType\UrlItemMatchType;
use Geeks4change\UntrackEmailAnalyzer\Globals;
use Geeks4change\UntrackEmailAnalyzer\Matcher2\MatcherManager;
use Geeks4change\UntrackEmailAnalyzer\UrlExtractor\ImagesUrlExtractor;
use Geeks4change\UntrackEmailAnalyzer\UrlExtractor\LinksUrlExtractor;
use Geeks4change\UntrackEmailAnalyzer\UrlExtractor\PixelsUrlExtractor;
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
      // Now...
      // - match urls for Unsubscribe
      // - match urls for UserTracking
      // - match urls for Analytics
      $urlItemInfoBag0 = UrlItemInfoBagBuilder::fromUrlItemBag($urlItemBag)->freeze();
      $urlItemInfoBag1 = $this->matcherManager->matchUnsubscribeUrls($urlItemInfoBag0);
      $urlItemInfoBag2 = $this->matcherManager->matchUserTrackingUrls($urlItemInfoBag1);
      $urlItemInfoBag3 = $this->analyticsMatcher->matchAnalyticsUrls($urlItemInfoBag2);

      $urlsToCrawlRedirect = $urlItemInfoBag3
        ->filter(fn(UrlItemInfo $info) => !$info->hasMatchOfType(UrlItemMatchType::Unsubscribe()))
        ->urlItems();

      $redirects = $this->redirectCrawler->crawlRedirects($urlsToCrawlRedirect);

      // @fixme Add redirects to UrlItemInfoBag.

      dump($redirects);
      exit();

      // Crawl analytics for urls and their redirects.





      // Extract links, images, pixels.
      $linkUrls = (new LinksUrlExtractor($crawler))->extract();
      $imageUrls = (new ImagesUrlExtractor($crawler))->extract();
      $allLinkAndImageUrlsList = new TypedUrlList($linkUrls, $imageUrls);
      $pixelsResult = (new PixelsUrlExtractor($crawler))->extract();

      // Match link and image urls.
      $matcher = new AllServicesLinkAndImageUrlListMatcher();
      ['exact' => $exactMatches, 'domain' => $domainMatches]
        = $matcher->generateMatches($allLinkAndImageUrlsList);

      // Fetch all resolved aliases.
      $domainAliasList = (new DomainAliasesResultFetcher())->fetch();

      $urlsWithRedirectList = ($this->redirectDetector)->detectRedirect($allLinkAndImageUrlsList, $unsubscribeUrlList);
      $urlWithAnalyticsList = (new AnalyticsDetector())->detectAnalytics($allLinkAndImageUrlsList);

      $resultDetails = new ResultDetails(
        $dkimResult,
        $headersResult,
        $allLinkAndImageUrlsList,
        $exactMatches,
        $domainMatches,
        $pixelsResult,
        $unsubscribeUrlList,
        $urlsWithRedirectList,
        $urlWithAnalyticsList,
        $domainAliasList
      );

      $resultSummary = (new ResultSummaryExtractor)
        ->extractResultSummary($resultDetails);
      $resultVerdict = (new ResultVerdictExtractor)
        ->extractResultVerdict($resultSummary);

      $listInfo = (new ListInfoExtractor())->extract($message);
      $messageInfo = (new MessageInfoExtractor())->extract($message);
      $analyzerResult = new FullResultWrapper($this->logger->freeze(), new FullResult($listInfo, $messageInfo, $resultVerdict, $resultSummary, $resultDetails));
    } catch (\xThrowable $e) {
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

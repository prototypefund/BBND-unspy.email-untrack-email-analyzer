<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\AggregatedSummary;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\AnalyzerLogger;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\AnalyzerResult;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\LinkAndImageUrlList;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\Report;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\NewsletterServicesMatcher\HeadersMatcher\AllServicesHeadersMatcher;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\NewsletterServicesMatcher\UrlsMatcher\AllServicesLinkAndImageUrlListMatcher;
use Geeks4change\UntrackEmailAnalyzer\Globals;
use Geeks4change\UntrackEmailAnalyzer\UrlExtractor\ImagesUrlExtractor;
use Geeks4change\UntrackEmailAnalyzer\UrlExtractor\LinksUrlExtractor;
use Geeks4change\UntrackEmailAnalyzer\UrlExtractor\PixelsUrlExtractor;
use Symfony\Component\DomCrawler\Crawler;
use ZBateson\MailMimeParser\MailMimeParser;

/**
 * @api This and its return classes are the only API for the outside.
 */
class Analyzer {

  public function analyze(string $rawMessage): AnalyzerResult {
    $logger = new AnalyzerLogger();

    // Check DKIM.
    $dkimResult = (new DKIMSignatureValidator())->validateDKIMSignature($rawMessage);

    // Parse and find patterns.
    $mailParser = new MailMimeParser();
    $message = $mailParser->parse($rawMessage, FALSE);
    // @todo Consider reporting unusual Mime parts, like more than one text/html part.

    // Analyze headers.
    $headersResult = (new AllServicesHeadersMatcher())->matchHeaders($message);

    $unsubscribeUrlList = (new UnsubscribeLinkExtractor())->extractUnsubscribeLink($message);

    // Analyze body html.
    $html = $message->getHtmlContent();
    // Do we need all the bells and whistles of HtmlPageCrawler and underlying
    // DomCrawler? Not really, but seems to add some namespace and encoding
    // safeguards that can not be wrong.
    $crawler = new Crawler($html);

    // Extract links, images, pixels.
    $linkUrls = (new LinksUrlExtractor($crawler))->extract();
    $imageUrls = (new ImagesUrlExtractor($crawler))->extract();
    $allLinkAndImageUrlsList = new LinkAndImageUrlList($linkUrls, $imageUrls);
    $pixelsResult = (new PixelsUrlExtractor($crawler))->extract();

    // Match link and image urls.
    $matcher = new AllServicesLinkAndImageUrlListMatcher();
    $linkAndImageUrlListResult = $matcher->generateLinkAndImageUrlListResults($allLinkAndImageUrlsList);

    // Fetch all resolved aliases.
    $domainAliasList = (new DomainAliasesResultFetcher())->fetch();

    $urlsWithRedirectList = (new RedirectDetector())->detectRedirect($allLinkAndImageUrlsList, $unsubscribeUrlList);
    $urlWithAnalyticsList = (new AnalyticsDetector())->detectAnalytics($allLinkAndImageUrlsList);

    // @todo Implement summary.
    $aggregated = new AggregatedSummary(NULL, '');

    Globals::deleteAll();

    $report = new Report(
      $aggregated,
      $dkimResult,
      $headersResult,
      $allLinkAndImageUrlsList,
      $linkAndImageUrlListResult,
      $pixelsResult,
      $unsubscribeUrlList,
      $urlsWithRedirectList,
      $urlWithAnalyticsList,
      $domainAliasList
    );
    return new AnalyzerResult($report, $logger->freeze());
  }

}

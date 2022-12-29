<?php

declare(strict_types=1);

namespace Geeks4change\BbndAnalyzer\Analyzer;

use Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult\AggregatedSummary;
use Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult\AnalyzerResult;
use Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult\LinkAndImageUrlList;
use Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult\UrlList;
use Geeks4change\BbndAnalyzer\Analyzer\NewsletterServicesMatcher\HeadersMatcher\AllServicesHeadersMatcher;
use Geeks4change\BbndAnalyzer\Analyzer\NewsletterServicesMatcher\UrlsMatcher\AllServicesLinkAndImageUrlListMatcher;
use Geeks4change\BbndAnalyzer\Globals;
use Geeks4change\BbndAnalyzer\UrlExtractor\ImagesUrlExtractor;
use Geeks4change\BbndAnalyzer\UrlExtractor\LinksUrlExtractor;
use Geeks4change\BbndAnalyzer\UrlExtractor\PixelsUrlExtractor;
use Masterminds\HTML5;
use ZBateson\MailMimeParser\MailMimeParser;

class Analyzer {

  public function analyze(string $emailWithHeaders): AnalyzerResult {
    // Check DKIM.
    $dkimResult = (new DKIMSignatureValidator())->validateDKIMSignature($emailWithHeaders);

    // Parse and find patterns.
    $mailParser = new MailMimeParser();
    $message = $mailParser->parse($emailWithHeaders, FALSE);
    // @todo Consider reporting unusual Mime parts, like more than one text/html part.

    // Analyze headers.
    $headersResult = (new AllServicesHeadersMatcher())->matchHeaders($message);

    $unsubscribeUrlList = (new UnsubscribeLinkExtractor())->extractUnsubscribeLink($message);

    // Analyze body html.
    $html = $message->getHtmlContent();
    $dom = (new HTML5(['disable_html_ns' => TRUE]))->loadHTML($html);

    // Extract links, images, pixels.
    // @todo Rewrite using Goutte.
    $linkUrls = (new LinksUrlExtractor($dom))->extract($html);
    $imageUrls = (new ImagesUrlExtractor($dom))->extract($html);
    $allLinkAndImageUrlsList = new LinkAndImageUrlList($linkUrls, $imageUrls);
    $pixelsResult = (new PixelsUrlExtractor($dom))->extract($html);

    // Match link and image urls.
    $matcher = new AllServicesLinkAndImageUrlListMatcher();
    $linkAndImageUrlListResult = $matcher->generateLinkAndImageUrlListResults($allLinkAndImageUrlsList);

    // Fetch all resolved aliases.
    $domainAliasList = (new DomainAliasesResultFetcher())->fetch();

    $urlsWithRedirectList = (new RedirectDetector())->detectRedirect($allLinkAndImageUrlsList, $unsubscribeUrlList);
    $urlWithAnalyticsList = (new AnalyticsDetector())->detectAnalytics($allLinkAndImageUrlsList);

    // @todo Replace with logger.
    $mayNeedResearch = Globals::get()->getMayNeedResearch();
    // @todo Implement summary.
    $aggregated = new AggregatedSummary(NULL, '');

    // Clean up.
    Globals::deleteAll();
    return new AnalyzerResult(
      $aggregated,
      $mayNeedResearch,
      $dkimResult,
      $headersResult,
      $allLinkAndImageUrlsList,
      $linkAndImageUrlListResult,
      $pixelsResult,
      $urlsWithRedirectList,
      $urlWithAnalyticsList,
      $domainAliasList
    );
  }

}

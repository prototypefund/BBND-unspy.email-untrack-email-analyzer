<?php

declare(strict_types=1);

namespace Geeks4change\BbndAnalyzer\Analyzer;

use Geeks4change\BbndAnalyzer\AnalyzerResult\AggregatedSummary;
use Geeks4change\BbndAnalyzer\AnalyzerResult\AnalyzerResult;
use Geeks4change\BbndAnalyzer\AnalyzerResult\LinkAndImageUrlList;
use Geeks4change\BbndAnalyzer\AnalyzerResult\UrlList;
use Geeks4change\BbndAnalyzer\Globals;
use Geeks4change\BbndAnalyzer\Html\ImageExtractor;
use Geeks4change\BbndAnalyzer\Html\LinkExtractor;
use Geeks4change\BbndAnalyzer\Html\PixelExtractor;
use Geeks4change\BbndAnalyzer\ServicesMatcher\HeadersMatcher\HeadersAllServicesMatcher;
use Geeks4change\BbndAnalyzer\ServicesMatcher\UrlsMatcher\LinkAndImageUrlListMatcher;
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
    $headersResult = (new HeadersAllServicesMatcher())->matchHeaders($message);

    // Analyze body html.
    $html = $message->getHtmlContent();
    $dom = (new HTML5(['disable_html_ns' => TRUE]))->loadHTML($html);

    // Extract links, images, pixels.
    $linkUrls = (new LinkExtractor($dom))->extract($html);
    $imageUrls = (new ImageExtractor($dom))->extract($html);
    $linkAndImageUrlList = new LinkAndImageUrlList($linkUrls, $imageUrls);
    $pixelsResult = (new PixelExtractor($dom))->extract($html);

    // Match link and image urls.
    $matcher = new LinkAndImageUrlListMatcher();
    $linkAndImageUrlListResult = $matcher->generateLinkAndImageUrlListResults($linkAndImageUrlList);

    // Fetch all resolved aliases.
    $domainAliasList = (new DomainAliasesResultFetcher())->fetch();

    // @fixme
    $urlsWithRedirectList = new UrlList();
    $urlWithAnalyticsList = new UrlList();


    $mayNeedResearch = Globals::get()->getMayNeedResearch();
    // @todo
    $aggregated = new AggregatedSummary(NULL, '');

    // Clean up.
    Globals::deleteAll();
    return new AnalyzerResult($aggregated, $mayNeedResearch, $dkimResult, $headersResult, $linkAndImageUrlListResult, $pixelsResult, $urlsWithRedirectList, $urlWithAnalyticsList, $domainAliasList);
  }

}

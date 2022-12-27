<?php

declare(strict_types=1);

namespace Geeks4change\BbndAnalyzer\Analysis;

use Geeks4change\BbndAnalyzer\Analysis\Summary\LinkAndImageUrlList;
use Geeks4change\BbndAnalyzer\Analysis\Summary\AggregatedSummary;
use Geeks4change\BbndAnalyzer\Analysis\Summary\AnalyzerResult;
use Geeks4change\BbndAnalyzer\Globals;
use Geeks4change\BbndAnalyzer\Html\ImageExtractor;
use Geeks4change\BbndAnalyzer\Html\LinkExtractor;
use Geeks4change\BbndAnalyzer\Html\PixelExtractor;
use Geeks4change\BbndAnalyzer\ServicesMatching\LinkAndImageUrlListMatcher;
use Masterminds\HTML5;
use ZBateson\MailMimeParser\MailMimeParser;

class Analyzer {

  public function analyze(string $emailWithHeaders): AnalyzerResult {
    // Check DKIM.
    $dkimResult = (new DKIMAnalyzer())->analyzeDKIM($emailWithHeaders);

    // Parse and find patterns.
    $mailParser = new MailMimeParser();
    $message = $mailParser->parse($emailWithHeaders, FALSE);
    // @todo Consider reporting unusual Mime parts, like more than one text/html part.

    // Analyze headers.
    $headersResult = (new ServiceHeaderAnalyzer())->analyzeHeaders($message);

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

    $mayNeedResearch = Globals::get()->getMayNeedResearch();
    // @todo
    $aggregated = new AggregatedSummary(NULL, '');

    // Clean up.
    Globals::deleteAll();
    return new AnalyzerResult($aggregated, $mayNeedResearch, $dkimResult, $headersResult, $linkAndImageUrlListResult, $pixelsResult, $domainAliasList);
  }

}

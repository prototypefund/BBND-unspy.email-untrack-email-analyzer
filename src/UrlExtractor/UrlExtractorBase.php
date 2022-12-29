<?php

declare(strict_types=1);

namespace Geeks4change\BbndAnalyzer\UrlExtractor;

use Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult\UrlList;
use Geeks4change\BbndAnalyzer\Utility\UrlTool;
use GuzzleHttp\Psr7\Uri;
use Wa72\HtmlPageDom\HtmlPageCrawler;

/**
 * @internal
 */
abstract class UrlExtractorBase {

  protected HtmlPageCrawler $crawler;

  /**
   * @param \Wa72\HtmlPageDom\HtmlPageCrawler $crawler
   */
  public function __construct(HtmlPageCrawler $crawler) {
    $this->crawler = $crawler;
  }


  public function extract(): UrlList {
    $rawUrls = $this->extractUrls();
    $result = new UrlList();
    foreach ($rawUrls as $urlNode) {
      $url = $urlNode->value;
      if (UrlTool::isWebUrl(new Uri($url))) {
        $result->add($url);
      }
    }
    return $result;
  }

  abstract protected function extractUrls(): \Traversable;

}

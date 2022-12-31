<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\UrlExtractor;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\UrlList;
use Geeks4change\UntrackEmailAnalyzer\Utility\UrlTool;
use GuzzleHttp\Psr7\Uri;
use Symfony\Component\DomCrawler\Crawler;

/**
 * @internal
 */
abstract class UrlExtractorBase {

  protected Crawler $crawler;

  public function __construct(Crawler $crawler) {
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

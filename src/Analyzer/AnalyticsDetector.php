<?php

declare(strict_types=1);

namespace Geeks4change\BbndAnalyzer\Analyzer;

use Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult\LinkAndImageUrlList;
use Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult\UrlList;
use GuzzleHttp\Psr7\Query;

/**
 * Recognizes google / piwik / matomo analytics in links.
 */
final class AnalyticsDetector {

  public function detectAnalytics(LinkAndImageUrlList $linkAndImageUrlList): LinkAndImageUrlList {
    return new LinkAndImageUrlList(
      $this->doDetectAnalytics($linkAndImageUrlList->getLinkUrlList()),
      $this->doDetectAnalytics($linkAndImageUrlList->getImageUrlList()),
    );
  }

  protected function doDetectAnalytics(UrlList $urlList): UrlList {
    $analyticsUrlList = new UrlList();
    foreach ($urlList as $urlWrapper) {
      $url = $urlWrapper->getUrlObject();
      $rawQuery = $url->getQuery();
      $query = Query::parse($rawQuery);
      $allQueryKeysOnSeparateLines = implode("\n", array_keys($query));
      $hasAnalytics = preg_match($this->getPattern(), $allQueryKeysOnSeparateLines);
      if ($hasAnalytics) {
        $analyticsUrlList->add(strval($urlWrapper));
      }
    }
    return $analyticsUrlList;
  }

  protected function getPattern(): string {
    $providers = '(utm_|matomo_|mtm_|piwik_|pk_|)';
    $parameters = '(source|medium|campaign|term|content|keyword|kwd)';
    // Preg modifiers: unicode, multiline (^$)
    return "~^{$providers}{$parameters}$~um";
  }

}

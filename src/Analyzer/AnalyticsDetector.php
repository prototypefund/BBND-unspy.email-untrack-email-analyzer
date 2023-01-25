<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\TypedUrlList;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\UrlList;
use GuzzleHttp\Psr7\Query;

/**
 * Recognizes google / piwik / matomo analytics in links.
 */
final class AnalyticsDetector {

  public function detectAnalytics(TypedUrlList $linkAndImageUrlList): TypedUrlList {
    return new TypedUrlList(
      $this->doDetectAnalytics($linkAndImageUrlList->typeLink),
      $this->doDetectAnalytics($linkAndImageUrlList->typeLink),
    );
  }

  protected function doDetectAnalytics(UrlList $urlList): UrlList {
    $analyticsKeys = (new AnalyticsKeyInfo)->getKeys();
    $analyticsUrlList = UrlList::builder();
    foreach ($urlList as $urlWrapper) {
      $url = $urlWrapper->getUrlObject();
      $rawQuery = $url->getQuery();
      $query = Query::parse($rawQuery);
      $hasAnalytics = array_intersect(array_keys($query), $analyticsKeys);
      if ($hasAnalytics) {
        $analyticsUrlList->add(strval($urlWrapper));
      }
    }
    return $analyticsUrlList->freeze();
  }

}

<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer2;

use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\UrlItem;
use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\UrlItemInfoBag;
use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\UrlItemInfoBagBuilder;
use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\UrlItemMatchType;
use GuzzleHttp\Psr7\Uri;

final class AnalyticsMatcher {

  public function matchAnalyticsUrls(UrlItemInfoBag $urlItemInfoBag): UrlItemInfoBag {
    $builder = UrlItemInfoBagBuilder::fromUrlItemInfoBag($urlItemInfoBag);
    foreach ($urlItemInfoBag->urlItemInfos as $urlItemInfo) {
      $urlItem = $urlItemInfo->urlItem;
      if ($this->matchAnalyticsUrl($urlItem)) {
        $builder->addCreateMatch($urlItem, '_analytics', UrlItemMatchType::Analytics);
      }
    }
    return $builder->freeze();
  }

  protected function matchAnalyticsUrl(UrlItem $urlItem): bool {
    return FALSE;
  }

  protected function getQuery(UrlItem $urlItem): array {
    $queryAsString = (new Uri($urlItem->url))
      ->getQuery();
    parse_str($queryAsString, $queryAsArray);
    return $queryAsArray;
  }

}

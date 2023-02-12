<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer2;

use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\UrlItem;
use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\UrlItemInfoBag;
use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\UrlItemInfoBagBuilder;
use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\UrlItemMatchType\Analytics;
use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\UrlItemMatchType\AnalyticsMatchType;
use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\UrlItemMatchType\UrlItemMatchType;
use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\UrlItemType;
use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\UrlRedirectBag;
use GuzzleHttp\Psr7\Uri;

final class AnalyticsMatcher {

  protected array $pseudonymousKeys;

  public function matchAnalyticsUrls(UrlItemInfoBag $urlItemInfoBag, UrlRedirectBag $urlRedirectBag): UrlItemInfoBag {
    $builder = UrlItemInfoBagBuilder::fromUrlItemInfoBag($urlItemInfoBag);
    foreach ($urlItemInfoBag->urlItemInfos as $urlItemInfo) {
      // Only look on links for now.
      if ($urlItemInfo->urlItem->type !== UrlItemType::Link) {
        continue;
      }
      $urlItem = $urlItemInfo->urlItem;
      $redirect = $urlRedirectBag->getRedirect($urlItem);
      if ($redirect) {
        // Only look in the effective url.
        $effectiveUrlItem = $redirect->getEffectiveUrlItem();
        if ($analyticsMatchType = $this->matchAnalyticsUrl($effectiveUrlItem)) {
          $builder->addCreateMatch($urlItem, '_analytics', $analyticsMatchType);
        }
      }
    }
    return $builder->freeze();
  }

  protected function matchAnalyticsUrl(UrlItem $urlItem): ?Analytics {
    $queryKeys = array_keys($this->getQuery($urlItem));
    if (!$queryKeys) {
      return NULL;
    }
    elseif (array_intersect($queryKeys, $this->getUserTrackingKeys())) {
      return UrlItemMatchType::Analytics(AnalyticsMatchType::LooksLikeUserTracking, $queryKeys);
    }
    elseif (array_diff($queryKeys, $this->getPseudonymousKeys())) {
      return UrlItemMatchType::Analytics(AnalyticsMatchType::NeedsResearch, $queryKeys);
    }
    else {
      return UrlItemMatchType::Analytics(AnalyticsMatchType::LooksHarmless, $queryKeys);
    }
  }

  protected function getQuery(UrlItem $urlItem): array {
    $queryAsString = (new Uri($urlItem->url))
      ->getQuery();
    parse_str($queryAsString, $queryAsArray);
    return $queryAsArray;
  }

  protected function getUserTrackingKeys(): array {
    return [
      // Mailchimp.
      'mc_eid',
    ];
  }

  protected function getPseudonymousKeys(): array {
    return $this->pseudonymousKeys
      ?? ($this->pseudonymousKeys = $this->createPseudonymousKeys());
  }

  protected function createPseudonymousKeys(): array {
    $providers = explode('|', 'utm_|matomo_|mtm_|piwik_|pk_|');
    $parameters = explode('|', 'source|medium|campaign|term|content|keyword|kwd');
    $keys = [];
    foreach ($providers as $provider) {
      foreach ($parameters as $parameter) {
        $key = "{$provider}{$parameter}";
        $keys[] = $key;
      }
    }
    // Mailchimp.
    $keys[] = 'mc_cid';
    return $keys;
  }

}

<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer2;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultVerdict\ResultVerdict;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultVerdict\ResultVerdictMatchLevel;
use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Header\HeaderItemInfoBag;
use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\UrlItemInfoBag;
use Geeks4change\UntrackEmailAnalyzer\Analyzer2\ResultDetails\ResultDetails;

final class ResultVerdictExtractor {

  public function __construct() {
  }

  public function extractResultVerdict(ResultDetails $details): ResultVerdict {


    $providersHeader = $this->extractHeaderProviders($details->headerItemInfoBag);
    $providersUserTracking = $this->extractUserTrackingProviders($details->urlItemInfoBag);
    $providersDomain = $this->extractByDomainProviders($details->urlItemInfoBag);
    $redirects = $this->extractRedirects($details->urlItemInfoBag);

    // Header and exact matches point to one provider.
    if ($providersHeader === $providersUserTracking && count($providersHeader) === 1) {
      return new ResultVerdict(ResultVerdictMatchLevel::Sure, reset($providersHeader));
    }

    // There is one provider that has header or link matches, but not both.
    $providersExactOrDomain = array_merge($providersUserTracking, $providersDomain);
    $providersHeadersAndExactOrDomain = array_intersect($providersHeader, $providersExactOrDomain);
    if (count($providersHeadersAndExactOrDomain) === 1) {
      return new ResultVerdict(ResultVerdictMatchLevel::Likely, reset($providersHeadersAndExactOrDomain));
    }

    // Redirects indicate user tracking.
    if ($redirects) {
      return new ResultVerdict(ResultVerdictMatchLevel::Likely);
    }

    // No redirects, so user tracking is unlikely.
    return new ResultVerdict(ResultVerdictMatchLevel::Unlikely);
  }

  private function extractHeaderProviders(HeaderItemInfoBag $headerItemInfoBag): array {
    $providers = [];
    foreach ($headerItemInfoBag->infos as $info) {
      foreach ($info->matches as $match) {
        $providers[] = $match->matcherId;
      }
    }
    return array_unique($providers);
  }

  private function extractUserTrackingProviders(UrlItemInfoBag $urlItemInfoBag): array {
    $providers = [];
    foreach ($urlItemInfoBag->urlItemInfos as $urlItemInfo) {
      $providers = array_merge($providers, array_keys($urlItemInfo->userTrackingUrlMatchesById ?? []));
    }
    return array_unique($providers);
  }

  private function extractByDomainProviders(UrlItemInfoBag $urlItemInfoBag): array {
    $providers = [];
    foreach ($urlItemInfoBag->urlItemInfos as $urlItemInfo) {
      $providers = array_merge($providers, array_keys($urlItemInfo->userTrackingUrlMatchesById ?? []));
    }
    return array_unique($providers);
  }

  private function extractRedirects(UrlItemInfoBag $urlItemInfoBag): int {
    $redirects = 0;
    foreach ($urlItemInfoBag->urlItemInfos as $urlItemInfo) {
      if ($urlItemInfo->redirectInfo) {
        $redirects++;
      }
    }
    return $redirects;
  }


}

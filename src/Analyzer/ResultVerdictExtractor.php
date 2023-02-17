<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\Result\Header\HeaderItemInfoBag;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\Result\ResultDetails;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\Result\Url\Analytics\AnalyticsMatchType;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\Result\Url\UrlItemInfoBag;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\Verdict\ResultVerdict;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\Verdict\ResultVerdictMatchLevel;

final class ResultVerdictExtractor {

  public function __construct() {
  }

  public function extractResultVerdict(ResultDetails $details): ResultVerdict {

    $providersHeader = $this->getHeaderProviderIds($details->headerItemInfoBag);
    $providersUserTracking = $this->getUserTrackingProviderIds($details->urlItemInfoBag);
    $providersAnyMatch = $this->getAllMatchingProviderIds($details->urlItemInfoBag);
    $redirectCount = $this->getRedirectCount($details->urlItemInfoBag);
    $userTrackingAnalyticsCount = $this->getUserTrackingAnalyticsCount($details->urlItemInfoBag);

    // Header and exact matches point to one provider.
    if ($providersHeader === $providersUserTracking && count($providersHeader) === 1) {
      return new ResultVerdict(
        ResultVerdictMatchLevel::Sure,
        reset($providersHeader),
        'Header and body matches point to exactly one provider.'
      );
    }

    // There is one provider that has header or link matches, but not both.
    $providersHeadersAndExactOrDomain = array_intersect($providersHeader, $providersAnyMatch);
    if (count($providersHeadersAndExactOrDomain) === 1) {
      return new ResultVerdict(
        ResultVerdictMatchLevel::Likely,
        reset($providersHeadersAndExactOrDomain),
        'There are matches for well-known tracking patterns, but no provider matches header and body.'
      );
    }

    // Redirects indicate user tracking.
    if ($redirectCount) {
      return new ResultVerdict(
        ResultVerdictMatchLevel::Likely,
        NULL,
        'No well-known tracking pattern matches, but there are redirect links.'
      );
    }

    // Redirects indicate user tracking.
    if ($userTrackingAnalyticsCount) {
      return new ResultVerdict(
        ResultVerdictMatchLevel::Likely,
        NULL,
        'No provider or redirect patterns, but well-known spy analytics keys.'
      );
    }

    // No redirects, so user tracking is unlikely.
    return new ResultVerdict(
      ResultVerdictMatchLevel::Unlikely,
      NULL,
      'No tracking pattern and no redirect link found.'
    );
  }

  private function getHeaderProviderIds(HeaderItemInfoBag $headerItemInfoBag): array {
    $providers = [];
    foreach ($headerItemInfoBag->infos as $info) {
      foreach ($info->matches as $match) {
        $providers[] = $match->providerId;
      }
    }
    return array_unique($providers);
  }

  private function getUserTrackingProviderIds(UrlItemInfoBag $urlItemInfoBag): array {
    $providers = [];
    foreach ($urlItemInfoBag->urlItemInfos as $urlItemInfo) {
      $trackingProviderIds = $urlItemInfo->getUserTrackingProviderIds() ?? [];
      $providers = array_merge($providers, $trackingProviderIds);
    }
    return array_unique($providers);
  }

  private function getAllMatchingProviderIds(UrlItemInfoBag $urlItemInfoBag): array {
    $providers = [];
    foreach ($urlItemInfoBag->urlItemInfos as $urlItemInfo) {
      $providers = array_merge($providers, array_keys($urlItemInfo->matches ?? []));
    }
    return array_unique($providers);
  }

  private function getRedirectCount(UrlItemInfoBag $urlItemInfoBag): int {
    $redirects = 0;
    foreach ($urlItemInfoBag->urlItemInfos as $urlItemInfo) {
      if ($urlItemInfo->redirectInfo?->redirect) {
        $redirects++;
      }
    }
    return $redirects;
  }

  private function getUserTrackingAnalyticsCount(UrlItemInfoBag $urlItemInfoBag): int {
    $userTrackingAnalytics = 0;
    foreach ($urlItemInfoBag->urlItemInfos as $urlItemInfo) {
      if ($urlItemInfo->analyticsInfo?->type === AnalyticsMatchType::LooksLikeUserTracking) {
        $userTrackingAnalytics++;
      }
    }
    return $userTrackingAnalytics;
  }

}

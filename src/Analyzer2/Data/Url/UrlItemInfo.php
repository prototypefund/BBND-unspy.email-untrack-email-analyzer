<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url;

use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\UrlItemMatchType\AnalyticsInfo;
use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\UrlItemMatchType\RedirectInfo;

final class UrlItemInfo {

  /**
   * @param array<string, \Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\UrlItemMatchType\TechnicalUrlMatch> $technicalUrlMatchesById
   * @param array<string, \Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\UrlItemMatchType\UserTrackingUrlMatch> $userTrackingUrlMatchesById
   * @param array<string, \Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\UrlItemMatchType\ByDomainUrlMatch> $byDomainUrlMatchesById
   */
  public function __construct(
    public readonly UrlItem        $urlItem,
    public readonly ?RedirectInfo  $redirectInfo,
    public readonly ?AnalyticsInfo $analyticsInfo,
    public readonly ?array         $technicalUrlMatchesById,
    public readonly ?array         $userTrackingUrlMatchesById,
    public readonly ?array         $byDomainUrlMatchesById,
  ) {}

}

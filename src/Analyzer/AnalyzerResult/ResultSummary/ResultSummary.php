<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultSummary;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\CnameChainList;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\DKIMResult;

/**
 * Summary that contains only counts, no links or personal data
 *
 * What?
 * - DKIM status? Copy.
 * - Headers result? Only counts.
 * - Urls / Matches / Pixels? Only counts.
 * - RedirectInfo? Only counts.
 * - UrlsWithAnalytics ? Counts, extra keys.
 * - Aliases? Copy.
 */
final class ResultSummary {

  public function __construct(
    public readonly DKIMResult                    $dkimResult,
    public readonly HeaderMatchSummaryPerProvider $headerMatches,
    public readonly TypedUrlCount                 $typedUrlCount,
    public readonly TypedUrlCountPerProvider      $exactMatches,
    public readonly TypedUrlCountPerProvider      $domainMatches,
    public readonly int                           $pixelsCount,
    public readonly TypedUrlRedirectCount         $typedUrlRedirectCount,
    public readonly TypedAnalyticsKeyList         $typedAnalyticsKeyList,
    public readonly CnameChainList                $cnameChainList,
  ) {}

}

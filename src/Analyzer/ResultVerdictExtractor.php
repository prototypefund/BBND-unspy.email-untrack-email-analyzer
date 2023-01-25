<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultSummary\ResultSummary;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultVerdict\ResultVerdict;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultVerdict\ResultVerdictMatchLevel;

final class ResultVerdictExtractor {

  public function __construct() {
  }

  public function extractResultVerdict(ResultSummary $summary): ResultVerdict {
    $providersHeader = $summary->headerMatches->keys();
    $providersExact = $summary->exactMatches->keys();
    $providersDomain = $summary->domainMatches->keys();

    // Header and exact matches point to one provider.
    if ($providersHeader === $providersExact && count($providersHeader) === 1) {
      return new ResultVerdict(ResultVerdictMatchLevel::Sure, reset($providersHeader));
    }

    // There is one provider that has header and link matches.
    $providersExactOrDomain = array_merge($providersExact, $providersDomain);
    $providersHeadersAndExactOrDomain = array_intersect($providersHeader, $providersExactOrDomain);
    if (count($providersHeadersAndExactOrDomain) === 1) {
      return new ResultVerdict(ResultVerdictMatchLevel::Likely, reset($providersHeadersAndExactOrDomain));
    }

    // Redirects indicate user tracking.
    if ($summary->redirects->typeLink) {
      return new ResultVerdict(ResultVerdictMatchLevel::Likely);
    }

    // No redirects, so user tracking is unlikely.
    return new ResultVerdict(ResultVerdictMatchLevel::Unlikely);
  }


}

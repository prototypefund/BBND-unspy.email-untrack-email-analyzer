<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\ResultDetails;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultSummary\ResultSummary;

final class ResultSummaryExtractor {

  public function extractResultSummary(ResultDetails $resultDetails): ResultSummary {
    // @fixme
    return new ResultSummary();
  }

}

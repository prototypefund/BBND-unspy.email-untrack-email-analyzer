<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultSummary\ResultSummary;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultVerdict\ResultVerdict;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultVerdict\ResultVerdictMatchLevel;

final class ResultVerdictExtractor {

  public function __construct() {
  }

  public function extractResultVerdict(ResultSummary $resultSummary) {
    // @fixme
    $serviceName = NULL;
    $matchLevel = ResultVerdictMatchLevel::Unknown;
    /** @noinspection PhpUnnecessaryLocalVariableInspection */
    $resultSummary = new ResultVerdict($serviceName, $matchLevel);
    return $resultSummary;
  }


}

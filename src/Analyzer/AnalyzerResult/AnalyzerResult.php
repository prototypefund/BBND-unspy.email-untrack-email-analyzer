<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\AnalyzerLog\AnalyzerLog;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\ResultDetails;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultSummary\ResultSummary;

/**
 * Analyzer result.
 *
 * @api Will be serialized in persistent storage, any change needs a migration.
 */
final class AnalyzerResult {

  public function __construct(
    public readonly ResultDetails $resultDetails,
    public readonly AnalyzerLog   $fullLog,
    public readonly ResultSummary $resultSummary,
    public readonly AnalyzerLog   $sanitizedLog
  ) {}

}

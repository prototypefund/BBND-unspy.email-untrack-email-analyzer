<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult;

/**
 * Analyzer result.
 *
 * For structure,
 *
 * @see \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails
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

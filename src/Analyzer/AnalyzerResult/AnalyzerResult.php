<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult;

/**
 * Analyzer result.
 *
 * For structure,
 *
 * @see \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultSummaryPart
 *
 * @api Will be serialized in persistent storage, any change needs a migration.
 */
final class AnalyzerResult {

  protected ResultSummaryPart $report;

  protected AnalyzerLog $log;

  /**
   * @param \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultSummaryPart $report
   * @param \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\AnalyzerLog $log
   */
  public function __construct(ResultSummaryPart $report, AnalyzerLog $log) {
    $this->report = $report;
    $this->log = $log;
  }

  /**
   * @return \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultSummaryPart
   */
  public function getReport(): ResultSummaryPart {
    return $this->report;
  }

  /**
   * @return \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\AnalyzerLog
   */
  public function getLog(): AnalyzerLog {
    return $this->log;
  }


}

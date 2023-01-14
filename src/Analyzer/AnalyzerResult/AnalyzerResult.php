<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult;

/**
 * Analyzer result.
 *
 * For structure,
 *
 * @see \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultSummary
 *
 * @api Will be serialized in persistent storage, any change needs a migration.
 */
final class AnalyzerResult {

  protected ResultSummary $report;

  protected AnalyzerLog $log;

  /**
   * @param \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultSummary $report
   * @param \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\AnalyzerLog $log
   */
  public function __construct(ResultSummary $report, AnalyzerLog $log) {
    $this->report = $report;
    $this->log = $log;
  }

  /**
   * @return \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultSummary
   */
  public function getReport(): ResultSummary {
    return $this->report;
  }

  /**
   * @return \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\AnalyzerLog
   */
  public function getLog(): AnalyzerLog {
    return $this->log;
  }


}

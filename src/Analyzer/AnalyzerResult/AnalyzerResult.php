<?php

declare(strict_types=1);

namespace Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult;

final class AnalyzerResult {

  protected Report $report;

  protected AnalyzerLog $log;

  /**
   * @param \Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult\Report $report
   * @param \Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult\AnalyzerLog $log
   */
  public function __construct(Report $report, AnalyzerLog $log) {
    $this->report = $report;
    $this->log = $log;
  }

  /**
   * @return \Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult\Report
   */
  public function getReport(): Report {
    return $this->report;
  }

  /**
   * @return \Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult\AnalyzerLog
   */
  public function getLog(): AnalyzerLog {
    return $this->log;
  }


}

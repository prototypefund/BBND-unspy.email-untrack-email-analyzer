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

  protected ResultSummary $resultSummary;

  protected AnalyzerLog $fullLog;

  protected ResultDetails $resultDetails;

  protected AnalyzerLog $sanitizedLog;

  /**
   * @param \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultSummary $resultSummary
   * @param \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\AnalyzerLog $fullLog
   * @param \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails $resultDetails
   * @param \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\AnalyzerLog $sanitizedLog
   */
  public function __construct(ResultSummary $resultSummary, AnalyzerLog $fullLog, ResultDetails $resultDetails, AnalyzerLog $sanitizedLog) {
    $this->resultSummary = $resultSummary;
    $this->fullLog = $fullLog;
    $this->resultDetails = $resultDetails;
    $this->sanitizedLog = $sanitizedLog;
  }

  /**
   * @return \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultSummary
   */
  public function getResultSummary(): ResultSummary {
    return $this->resultSummary;
  }

  /**
   * @return \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\AnalyzerLog
   */
  public function getFullLog(): AnalyzerLog {
    return $this->fullLog;
  }

  /**
   * @return \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails
   */
  public function getResultDetails(): ResultDetails {
    return $this->resultDetails;
  }

  /**
   * @return \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\AnalyzerLog
   */
  public function getSanitizedLog(): AnalyzerLog {
    return $this->sanitizedLog;
  }


}

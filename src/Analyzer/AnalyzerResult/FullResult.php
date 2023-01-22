<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\AnalyzerLog\AnalyzerLog;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ListInfo\ListInfo;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\ResultDetails;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultSummary\ResultSummary;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultVerdict\ResultVerdict;

/**
 * Analyzer result.
 *
 * @api Will be serialized in persistent storage, any change needs a migration.
 */
final class FullResult {

  public function __construct(
    public readonly ListInfo      $listInfo,
    public readonly ResultDetails $resultDetails,
    public readonly ResultSummary $resultSummary,
    public readonly ResultVerdict $resultVerdict,
    public readonly AnalyzerLog   $log,
  ) {}

  /**
   * Create persistent result from full result.
   *
   * - Leaves away ResultDetails, as they contain recipient specific links.
   */
  public function getPersistentResult(): PersistentResult {
    return new PersistentResult(
      $this->listInfo,
      $this->resultSummary,
      $this->resultVerdict,
      $this->log,
    );
  }

}
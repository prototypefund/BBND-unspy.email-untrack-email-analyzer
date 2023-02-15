<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\Result;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\Result\PersistentResultWrapper;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\Log\AnalyzerLog;

/**
 * @internal
 */
final class FullResultWrapper {

  public function __construct(
    public readonly AnalyzerLog $log,
    public readonly ?FullResult  $fullResult,
  ) {}

  public function getPersistentResult(): PersistentResultWrapper {
    return new PersistentResultWrapper($this->log, $this->fullResult?->getPersistentResult());
  }


}

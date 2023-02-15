<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer2\ResultDetails;

use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Log\AnalyzerLog;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\PersistentResultWrapper;

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

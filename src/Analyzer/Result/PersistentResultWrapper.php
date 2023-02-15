<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\Result;


use Geeks4change\UntrackEmailAnalyzer\Analyzer\Log\AnalyzerLog;

/**
 * @internal
 */
final class PersistentResultWrapper {

  public function __construct(
    public readonly AnalyzerLog        $log,
    public readonly ?PersistentResult  $persistentResult,
  ) {}

}

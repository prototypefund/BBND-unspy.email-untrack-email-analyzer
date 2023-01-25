<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\AnalyzerLog\AnalyzerLog;

/**
 * @internal
 */
abstract class FullResultBase {

  public function __construct(
    public readonly AnalyzerLog   $log,
  ) {}

  abstract public function getPersistentResult(): PersistentResultBase;

}

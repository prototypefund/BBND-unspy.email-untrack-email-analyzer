<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\AnalyzerLog\AnalyzerLog;

/**
 * @internal
 */
abstract class PersistentResultBase {

  public function __construct(
    public readonly AnalyzerLog   $log,
  ) {}

}

<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\Verdict;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\Arrayable\ToArrayInterface;

final class ResultVerdict {

  public function __construct(
    public readonly ResultVerdictMatchLevel $matchLevel,
    public readonly ?string                 $serviceName,
    public readonly string                  $explanation,
  ) {}

}

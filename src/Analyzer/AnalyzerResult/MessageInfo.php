<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult;

final class MessageInfo {

  public function __construct(
    public readonly int $timeStamp,
  ) {}


}

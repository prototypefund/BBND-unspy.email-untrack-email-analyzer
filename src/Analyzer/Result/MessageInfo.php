<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\Result;

final class MessageInfo {

  public function __construct(
    public readonly int $timeStamp,
  ) {}


}

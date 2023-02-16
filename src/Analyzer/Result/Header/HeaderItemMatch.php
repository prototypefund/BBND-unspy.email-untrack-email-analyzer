<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\Result\Header;

final class HeaderItemMatch {

  public function __construct(
    public readonly string $matcherId,
    public bool $isMatch,
  ) {}

}

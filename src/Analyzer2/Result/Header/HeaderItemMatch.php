<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer2\Result\Header;

final class HeaderItemMatch {

  public function __construct(
    public readonly string $matcherId,
    // @fixme Add HeaderItemMatch::isMatch
    public bool $isMatch,
  ) {}

}

<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer2\ResultSummary;

final class TypedUrlCount {

  public function __construct(
    public readonly int $typeLink,
    public readonly int $typeImage,
  ) {}

}

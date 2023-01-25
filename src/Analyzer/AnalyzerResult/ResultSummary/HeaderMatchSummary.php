<?php

declare(strict_types=1);
namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultSummary;

final class HeaderMatchSummary {

  /**
   * @param array<string> $matchNames
   * @param array<string> $nonMatchNames
   */
  public function __construct(
    public readonly array $matchNames,
    public readonly array $nonMatchNames,
  ) {}

}

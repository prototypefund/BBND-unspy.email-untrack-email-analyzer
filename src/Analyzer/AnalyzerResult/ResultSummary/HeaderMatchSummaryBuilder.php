<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultSummary;

final class HeaderMatchSummaryBuilder {

  protected array $matchNames = [];
  protected array $nonMatchNames = [];

  public function add(string $name, bool $isMatch) {
    if ($isMatch) {
      $this->matchNames[] = $name;
    }
    else {
      $this->nonMatchNames[] = $name;
    }
  }

  public function freeze(): HeaderMatchSummary {
    return new HeaderMatchSummary($this->matchNames, $this->nonMatchNames);
  }

}

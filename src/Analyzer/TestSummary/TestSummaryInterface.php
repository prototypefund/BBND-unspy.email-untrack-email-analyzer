<?php

namespace Geeks4change\BbndAnalyzer\Analyzer\TestSummary;

interface TestSummaryInterface {

  /**
   * Used in tests, so don't change lightheartedly.
   */
  public function getTestSummary(): array;

}
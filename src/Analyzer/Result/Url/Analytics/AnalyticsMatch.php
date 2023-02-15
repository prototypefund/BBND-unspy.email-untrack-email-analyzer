<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\Result\Url\Analytics;

final class AnalyticsMatch {

  /**
   * @param list<string> $keys
   */
  public function __construct(
    public readonly AnalyticsMatchType $type,
    public readonly array $keys,
  ) {}

}

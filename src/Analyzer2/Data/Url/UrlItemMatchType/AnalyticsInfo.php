<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\UrlItemMatchType;

final class AnalyticsInfo {

  /**
   * @param list<string> $keys
   */
  public function __construct(
    public readonly AnalyticsMatchType $type,
    public readonly array $keys,
  ) {}

}

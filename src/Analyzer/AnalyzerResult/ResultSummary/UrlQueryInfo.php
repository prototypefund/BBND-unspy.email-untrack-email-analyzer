<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultSummary;

final class UrlQueryInfo {

  /**
   * @param list<string> $analyticsKeys
   * @param list<string> $otherKeys
   */
  public function __construct(
    public readonly int $count,
    public readonly array $analyticsKeys,
    public readonly array $otherKeys,
  ) {}

  public static function builder(): UrlQueryInfoBuilder {
    return new UrlQueryInfoBuilder();
  }

}

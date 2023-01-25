<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultSummary;

final class HeaderMatchSummaryPerProvider {

  /**
   * @param array<string, \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultSummary\HeaderMatchSummary> $perProvider
   */
  public function __construct(
    public readonly array $perProvider,
  ) {}

  public static function builder(): HeaderMatchSummaryPerProviderBuilder {
    return new HeaderMatchSummaryPerProviderBuilder();
  }

}

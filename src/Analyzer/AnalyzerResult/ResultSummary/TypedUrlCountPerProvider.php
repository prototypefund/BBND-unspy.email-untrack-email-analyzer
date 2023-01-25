<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultSummary;

final class TypedUrlCountPerProvider {

  /**
   * @param array<string, \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultSummary\TypedUrlCount> $perProvider
   */
  public function __construct(
    public readonly array $perProvider,
  ) {}

  public static function builder(): TypedUrlCountPerProviderBuilder {
    return new TypedUrlCountPerProviderBuilder();
  }

}

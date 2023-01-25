<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultSummary;

/**
 * @implements \IteratorAggregate< string, \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultSummary\HeaderMatchSummary >
 */
final class HeaderMatchSummaryPerProvider implements \IteratorAggregate {

  /**
   * @param array<string, \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultSummary\HeaderMatchSummary> $perProvider
   */
  public function __construct(
    public readonly array $perProvider,
  ) {}

  public static function builder(): HeaderMatchSummaryPerProviderBuilder {
    return new HeaderMatchSummaryPerProviderBuilder();
  }

  public function getIterator(): \Traversable {
    return new \ArrayIterator($this->perProvider);
  }

  /**
   * @return list<string>
   */
  public function keys(): array {
    return array_keys($this->perProvider);
  }

}

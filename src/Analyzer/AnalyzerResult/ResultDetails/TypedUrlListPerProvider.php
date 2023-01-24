<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\TestSummary\TestSummaryInterface;
use loophp\collection\Collection;

/**
 * LinkAndImageUrlListPerProvider
 *
 * @implements \IteratorAggregate< string, \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\TypedUrlList >
 */
final class TypedUrlListPerProvider implements TestSummaryInterface, \IteratorAggregate {

  /**
   * @param array<\Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\TypedUrlList> $perProvider
   */
  public function __construct(
    protected readonly array $perProvider,
  ) {}

  public static function builder(): TypedUrlListPerProviderBuilder {
    return new TypedUrlListPerProviderBuilder();
  }

  public function getIterator(): \Traversable {
    return new \ArrayIterator($this->perProvider);
  }

  public function getTestSummary(): array {
    return Collection::fromIterable($this->perProvider)
      ->map(fn(TypedUrlList $typedUrlList) => $typedUrlList->getTestSummary())
      ->all(FALSE);
  }

}

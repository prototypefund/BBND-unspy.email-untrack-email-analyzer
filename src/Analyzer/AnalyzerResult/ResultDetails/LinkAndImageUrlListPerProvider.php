<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\TestSummary\TestSummaryInterface;
use loophp\collection\Collection;

/**
 * LinkAndImageUrlListPerProvider
 *
 * @implements \IteratorAggregate< string, \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\LinkAndImageUrlList >
 */
final class LinkAndImageUrlListPerProvider implements TestSummaryInterface, \IteratorAggregate {

  /**
   * @param array<\Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\LinkAndImageUrlList> $perProvider
   */
  public function __construct(
    protected readonly array $perProvider,
  ) {}

  public static function builder(): LinkAndImageUrlListPerProviderBuilder {
    return new LinkAndImageUrlListPerProviderBuilder();
  }

  public function getIterator(): \Traversable {
    return new \ArrayIterator($this->perProvider);
  }

  public function getTestSummary(): array {
    return Collection::fromIterable($this->perProvider)
      ->map(fn(LinkAndImageUrlList $typedUrlList) => $typedUrlList->getTestSummary())
      ->all(FALSE);
  }

}

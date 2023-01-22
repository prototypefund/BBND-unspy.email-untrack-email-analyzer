<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\Arrayable\ToArrayInterface;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\Arrayable\ToArrayTrait;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\TestSummary\TestSummaryInterface;

/**
 * Result of links / images per service, child of
 *
 * @implements \IteratorAggregate<string, \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\UrlListPerServiceMatches>
 *
 * @see \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\UrlListMatchersResult
 */
final class UrlListPerServiceMatchesList implements \IteratorAggregate, TestSummaryInterface, ToArrayInterface {

  use ToArrayTrait;

  /**
   * @param array<\Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\UrlListPerServiceMatches> $perService
   */
  public function __construct(
    public readonly array $perService,
  ) {}

  public function getIterator(): \Traversable {
    return new \ArrayIterator($this->perService);
  }

  public function getTestSummary(): array {
    return array_map(fn(UrlListPerServiceMatches $item) => $item->getTestSummary(), $this->perService);
  }

}

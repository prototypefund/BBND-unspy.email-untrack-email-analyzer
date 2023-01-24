<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\Arrayable\ToArrayInterface;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\Arrayable\ToArrayTrait;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\TestSummary\TestSummaryInterface;

/**
 * HeaderSummary, child of
 *
 * @see \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\ResultDetails
 *
 * @implements \IteratorAggregate<int, \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\HeaderMatchListPerProvider>
 *
 * @api Will be serialized in persistent storage, any change needs a migration.
 */
final class HeaderMatchListPerProvider implements \IteratorAggregate, TestSummaryInterface, ToArrayInterface {

  use ToArrayTrait;

  public function __construct(
    public readonly array $headersMatchResultPerServiceList = []
  ) {}

  public function getIterator(): \ArrayIterator {
    return new \ArrayIterator($this->headersMatchResultPerServiceList);
  }

  public function getTestSummary(): array {
    return [
      '_keys' => implode('*', array_keys($this->headersMatchResultPerServiceList)),
    ] + array_map(
      fn(HeaderMatchList $matchList) => $matchList->getTestSummary(),
      $this->headersMatchResultPerServiceList
    );
  }

}

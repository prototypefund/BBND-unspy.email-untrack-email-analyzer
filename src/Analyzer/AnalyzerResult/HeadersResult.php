<?php

declare(strict_types=1);

namespace Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult;

use Geeks4change\BbndAnalyzer\Analyzer\TestSummary\TestSummaryInterface;

/**
 * HeaderSummary, child of
 * @see \Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult\Report
 *
 * @implements \IteratorAggregate<int, \Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult\HeadersResultPerService>
 *
 * @api Will be serialized in persistent storage, any change needs a migration.
 */
final class HeadersResult implements \IteratorAggregate, TestSummaryInterface {

  /**
   * @var array<\Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult\HeadersResultPerService>
   */
  protected array $headersMatchResultPerServiceList = [];

  public function add(HeadersResultPerService $headersResultPerService) {
    $this->headersMatchResultPerServiceList[$headersResultPerService->getServiceName()] = $headersResultPerService;
  }

  public function getIterator(): \ArrayIterator {
    return new \ArrayIterator($this->headersMatchResultPerServiceList);
  }

  public function getTestSummary(): array {
    return [
      '_keys' => implode('*', array_keys($this->headersMatchResultPerServiceList)),
    ] + array_map(
      fn(HeadersResultPerService $hsps) => $hsps->getTestSummary(),
      $this->headersMatchResultPerServiceList
    );
  }

}

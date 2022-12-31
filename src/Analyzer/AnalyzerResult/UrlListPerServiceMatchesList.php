<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\TestSummary\TestSummaryInterface;

/**
 * Result of links / images per service, child of
 *
 * @implements \IteratorAggregate<string, \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\UrlListPerServiceMatches>
 *
 * @see \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\UrlListMatchersResult
 */
final class UrlListPerServiceMatchesList implements \IteratorAggregate, TestSummaryInterface {

  /**
   * @var array<\Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\UrlListPerServiceMatches>
   */
  protected array $perService = [];

  public function add(UrlListPerServiceMatches $perServiceMatches) {
    $this->perService[$perServiceMatches->getServiceName()] = $perServiceMatches;
  }

  public function getIterator(): \Traversable {
    return new \ArrayIterator($this->perService);
  }

  public function getTestSummary(): array {
    return array_map(fn(UrlListPerServiceMatches $item) => $item->getTestSummary(), $this->perService);
  }

}

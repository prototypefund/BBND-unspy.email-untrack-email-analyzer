<?php

declare(strict_types=1);

namespace Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult;

use Geeks4change\BbndAnalyzer\TestHelpers\TestSummaryInterface;

/**
 * Result of links / images per service, child of
 *
 * @see \Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult\UrlListMatchersResult
 */
final class UrlListPerServiceMatchesList implements \IteratorAggregate, TestSummaryInterface {

  /**
   * @var array<\Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult\UrlListPerServiceMatches>
   */
  protected array $perService = [];

  public function add(UrlListPerServiceMatches $perServiceMatches) {
    $this->perService[$perServiceMatches->getServiceName()] = $perServiceMatches;
  }

  public function getIterator(): \Traversable {
    return new \ArrayIterator($this->perService);
  }

  public function getTestSummary(): array {
    return array_map(fn(UrlListPerServiceMatches $ulpsm) => $ulpsm->getTestSummary(), $this->perService);
  }

}

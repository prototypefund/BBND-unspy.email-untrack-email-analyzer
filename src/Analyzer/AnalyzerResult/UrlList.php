<?php

declare(strict_types=1);

namespace Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult;

use Geeks4change\BbndAnalyzer\Analyzer\TestSummary\TestSummaryInterface;

/**
 * Url list summary, child of
 *
 * @see \Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult\Report
 * @see \Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult\UrlListMatchersResult
 * @see \Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult\UrlListPerServiceMatches
 *
 * @implements \IteratorAggregate<int, \Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult\Url>
 *
 * @api Will be serialized in persistent storage, any change needs a migration.
 * @internal
 */
final class UrlList implements \IteratorAggregate, TestSummaryInterface {

  /**
   * @var array<\Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult\Url>
   */
  protected array $urls = [];

  /**
   * @param string $url
   */
  public function add(string $url) {
    $this->urls[$url] = new Url($url);
  }

  public function count(): int {
    return count($this->urls);
  }

  public function contains(string $urlAsString) {
    return isset($this->urls[$urlAsString]);
  }

  public function getIterator(): \ArrayIterator {
    return new \ArrayIterator($this->urls);
  }

  public function getTestSummary(): array {
    return [
      '_count' => count($this->urls),
    ];
  }

}

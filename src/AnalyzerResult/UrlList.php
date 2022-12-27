<?php

declare(strict_types=1);

namespace Geeks4change\BbndAnalyzer\AnalyzerResult;

use Geeks4change\BbndAnalyzer\TestHelpers\TestSummaryInterface;

/**
 * Url list summary, child of
 *
 * @see \Geeks4change\BbndAnalyzer\AnalyzerResult\AnalyzerResult
 * @see \Geeks4change\BbndAnalyzer\AnalyzerResult\UrlListMatchersResult
 * @see \Geeks4change\BbndAnalyzer\AnalyzerResult\UrlListPerServiceMatches
 *
 * @implements \IteratorAggregate<int, \Geeks4change\BbndAnalyzer\AnalyzerResult\Url>
 *
 * @api Will be serialized in persistent storage, any change needs a migration.
 * @internal
 */
final class UrlList implements \IteratorAggregate, TestSummaryInterface {

  /**
   * @var array<\Geeks4change\BbndAnalyzer\AnalyzerResult\Url>
   */
  protected array $urls = [];

  /**
   * @param string|\Stringable $urlString
   */
  public function add($urlString) {
    // @todo Consider removing duplicates.
    $this->urls[] = new Url($urlString);
  }

  public function count(): int {
    return count($this->urls);
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

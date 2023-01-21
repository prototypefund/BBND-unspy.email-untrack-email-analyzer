<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\Arrayable\ToArrayInterface;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\Arrayable\ToArrayTrait;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\TestSummary\TestSummaryInterface;

/**
 * Url list summary, child of
 *
 * @see \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails
 * @see \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\UrlListMatchersResult
 * @see \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\UrlListPerServiceMatches
 *
 * @implements \IteratorAggregate<int, \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\UrlItem>
 *
 * @api Will be serialized in persistent storage, any change needs a migration.
 * @internal
 */
final class UrlList implements \IteratorAggregate, TestSummaryInterface, ToArrayInterface {

  use ToArrayTrait;

  /**
   * @param \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\UrlItem[] $urls
   */
  public function __construct(public readonly array $urls) {}

  public static function builder() {
    return new UrlListBuilder();
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

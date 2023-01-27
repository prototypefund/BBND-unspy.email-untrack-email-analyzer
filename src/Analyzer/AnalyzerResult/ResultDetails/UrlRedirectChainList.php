<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\Arrayable\ToArrayInterface;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\Arrayable\ToArrayTrait;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\TestSummary\TestSummaryInterface;

/**
 * @implements \IteratorAggregate<int, \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\UrlRedirectChain>
 *
 * @api Will be serialized in persistent storage, any change needs a migration.
 */
final class UrlRedirectChainList implements \IteratorAggregate, TestSummaryInterface, ToArrayInterface {

  use ToArrayTrait;

  /**
   * @param \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\UrlRedirectChain[] $urlRedirectChainList
   */
  public function __construct(
    protected readonly array $urlRedirectChainList
  ) {}

  public static function builder() {
    return new UrlRedirectChainListBuilder();
  }

  public function getIterator(): \ArrayIterator {
    return new \ArrayIterator($this->urlRedirectChainList);
  }

  public function getTestSummary(): array {
    return [
      '_count' => count($this->urlRedirectChainList),
    ];
  }

  public function get(string $url): ?UrlRedirectChain {
    return $this->urlRedirectChainList[$url] ?? NULL;
  }

}

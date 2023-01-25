<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\Arrayable\ToArrayInterface;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\Arrayable\ToArrayTrait;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\TestSummary\TestSummaryInterface;

/**
 * @implements \IteratorAggregate<int, \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\UrlRedirectInfo>
 *
 * @api Will be serialized in persistent storage, any change needs a migration.
 */
final class UrlRedirectInfoList implements \IteratorAggregate, TestSummaryInterface, ToArrayInterface {

  use ToArrayTrait;

  /**
   * @var array<\Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\UrlRedirectInfo>
   */
  protected array $urlRedirectInfoList = [];

  public function getIterator(): \ArrayIterator {
    return new \ArrayIterator($this->urlRedirectInfoList);
  }

  public function add(UrlRedirectInfo $urlRedirectInfo) {
    // @todo Add builder.
    if (!$urlRedirectInfo->redirectUrls) {
      throw new \UnexpectedValueException();
    }
    $this->urlRedirectInfoList[$urlRedirectInfo->url] = $urlRedirectInfo;
  }

  public function getTestSummary(): array {
    return [
      '_count' => count($this->urlRedirectInfoList),
    ];
  }

  public function get(string $url): ?UrlRedirectInfo {
    return $this->urlRedirectInfoList[$url] ?? NULL;
  }

}

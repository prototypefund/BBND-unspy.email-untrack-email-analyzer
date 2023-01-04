<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\TestSummary\TestSummaryInterface;

/**
 * @implements \IteratorAggregate<int, \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\UrlRedirectInfo>
 *
 * @api Will be serialized in persistent storage, any change needs a migration.
 */
final class UrlRedirectInfoList implements \IteratorAggregate, TestSummaryInterface {

  /**
   * @var array<\Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\UrlRedirectInfo>
   */
  protected array $urlRedirectInfoList = [];

  public function getIterator(): \ArrayIterator {
    return new \ArrayIterator($this->urlRedirectInfoList);
  }

  public function add(UrlRedirectInfo $urlRedirectInfo) {
    if (!$urlRedirectInfo->hasRedirect()) {
      throw new \UnexpectedValueException();
    }
    $this->urlRedirectInfoList[$urlRedirectInfo->getOriginalUrl()] = $urlRedirectInfo;
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

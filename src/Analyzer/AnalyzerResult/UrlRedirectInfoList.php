<?php

declare(strict_types=1);

namespace Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult;

use Geeks4change\BbndAnalyzer\Analyzer\TestSummary\TestSummaryInterface;

/**
 * @implements \IteratorAggregate<int, \Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult\DomainAliases>
 *
 * @api Will be serialized in persistent storage, any change needs a migration.
 */
final class UrlRedirectInfoList implements \IteratorAggregate, TestSummaryInterface {

  /**
   * @var array<\Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult\UrlRedirectInfo>
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

}

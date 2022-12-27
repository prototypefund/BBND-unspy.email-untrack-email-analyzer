<?php

declare(strict_types=1);

namespace Geeks4change\BbndAnalyzer\AnalyzerResult;

use Geeks4change\BbndAnalyzer\TestHelpers\TestSummaryInterface;

/**
 * HeaderSummary, child of
 *
 * @see \Geeks4change\BbndAnalyzer\AnalyzerResult\AnalyzerResult
 *
 * @api Will be serialized in persistent storage, any change needs a migration.
 */
final class HeadersResult implements TestSummaryInterface {

  /**
   * @var array<\Geeks4change\BbndAnalyzer\AnalyzerResult\HeadersResultPerService>
   */
  protected array $headersMatchResultPerServiceList = [];

  public function add(HeadersResultPerService $headersResultPerService) {
    $this->headersMatchResultPerServiceList[$headersResultPerService->getServiceName()] = $headersResultPerService;
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

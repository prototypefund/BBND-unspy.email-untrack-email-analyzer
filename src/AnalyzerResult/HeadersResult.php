<?php

declare(strict_types=1);

namespace Geeks4change\BbndAnalyzer\AnalyzerResult;

/**
 * HeaderSummary, child of
 *
 * @see \Geeks4change\BbndAnalyzer\AnalyzerResult\AnalyzerResult
 *
 * @api Will be serialized in persistent storage, any change needs a migration.
 */
final class HeadersResult {

  /**
   * @var array<\Geeks4change\BbndAnalyzer\AnalyzerResult\HeadersSummaryPerService>
   */
  protected array $headerSummaryPerServiceList;

  /**
   * @param \Geeks4change\BbndAnalyzer\AnalyzerResult\HeadersSummaryPerService[] $headerSummaryPerServiceList
   */
  public function __construct(array $headerSummaryPerServiceList) {
    $this->headerSummaryPerServiceList = $headerSummaryPerServiceList;
  }

  /**
   * @return array
   */
  public function getHeaderSummaryPerServiceList(): array {
    return $this->headerSummaryPerServiceList;
  }

}

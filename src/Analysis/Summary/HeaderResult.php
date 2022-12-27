<?php

declare(strict_types=1);

namespace Geeks4change\BbndAnalyzer\Analysis\Summary;

/**
 * HeaderSummary, child of
 * @see \Geeks4change\BbndAnalyzer\Analysis\Summary\AnalyzerResult
 *
 * @api Will be serialized in persistent storage, any change needs a migration.
 */
final class HeaderResult {

  /**
   * @var array<\Geeks4change\BbndAnalyzer\Analysis\Summary\HeaderSummaryPerService>
   */
  protected array $headerSummaryPerServiceList;

  /**
   * @param \Geeks4change\BbndAnalyzer\Analysis\Summary\HeaderSummaryPerService[] $headerSummaryPerServiceList
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

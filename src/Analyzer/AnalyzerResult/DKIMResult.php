<?php

declare(strict_types=1);
namespace Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult;

/**
 * DKIMSummary, child of
 * @see \Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult\Report
 *
 * @api Will be serialized in persistent storage, any change needs a migration.
 */
final class DKIMResult {

  /**
   * Status: green / yellow / red.
   *
   * @var string
   */
  protected string $status;

  /**
   * @var array<string>
   */
  protected array $summaryLines;

  /**
   * @param string $status
   * @param string[] $summaryLines
   */
  public function __construct(string $status, array $summaryLines) {
    $this->status = $status;
    $this->summaryLines = $summaryLines;
  }

  /**
   * @return string
   */
  public function getStatus(): string {
    return $this->status;
  }

  /**
   * @return array
   */
  public function getSummaryLines(): array {
    return $this->summaryLines;
  }


}

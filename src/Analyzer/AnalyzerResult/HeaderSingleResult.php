<?php

declare(strict_types=1);

namespace Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult;

use Geeks4change\BbndAnalyzer\TestHelpers\TestSummaryInterface;

/**
 * HeaderMatchSummary, child of
 *
 * @see \Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult\HeadersResultPerService
 *
 * @api Will be serialized in persistent storage, any change needs a migration.
 */
final class HeaderSingleResult implements TestSummaryInterface {

  protected string $headerName;

  protected bool $isMatch;

  /**
   * @param string $headerName
   * @param bool $isMatch
   */
  public function __construct(string $headerName, bool $isMatch) {
    $this->headerName = $headerName;
    $this->isMatch = $isMatch;
  }

  /**
   * @return string
   */
  public function getHeaderName(): string {
    return $this->headerName;
  }

  /**
   * @return bool
   */
  public function isMatch(): bool {
    return $this->isMatch;
  }

  public function getTestSummary(): array {
  }

}

<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\Arrayable\ToArrayInterface;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\Arrayable\ToArrayTrait;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\TestSummary\TestSummaryInterface;

/**
 * HeaderMatchSummary, child of
 *
 * @see \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\HeadersResultPerService
 *
 * @api Will be serialized in persistent storage, any change needs a migration.
 */
final class HeaderSingleResult implements TestSummaryInterface, ToArrayInterface {

  use ToArrayTrait;

  /**
   * @param string $headerName
   * @param bool $isMatch
   */
  public function __construct(
    public readonly string $headerName,
    public readonly bool   $isMatch
  ) {
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
    return [];
  }

}

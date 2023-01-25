<?php

declare(strict_types=1);
namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\Arrayable\ToArrayInterface;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\Arrayable\ToArrayTrait;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\TestSummary\TestSummaryInterface;

/**
 * HeaderMatchSummary, child of
 *
 * @see \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\HeaderMatchListPerProvider
 *
 * @api Will be serialized in persistent storage, any change needs a migration.
 */
final class HeaderMatch implements TestSummaryInterface, ToArrayInterface {

  use ToArrayTrait;

  public function __construct(
    public readonly string $headerName,
    public readonly string $headerValue,
    public readonly bool   $isMatch
  ) {
  }

  public function getTestSummary(): array {
    return [];
  }

}

<?php

declare(strict_types=1);
namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\Arrayable\ToArrayInterface;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\Arrayable\ToArrayTrait;

/**
 * DKIMSummary, child of
 *
 * @see \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\ResultDetails
 *
 * @api Will be serialized in persistent storage, any change needs a migration.
 */
final class DKIMResult implements ToArrayInterface {

  use ToArrayTrait;

  /**
   * @param string[] $summaryLines
   */
  public function __construct(
    public readonly DKIMStatusEnum $status,
    public readonly array $summaryLines
  ) {}

}

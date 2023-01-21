<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\Arrayable\ToArrayInterface;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\Arrayable\ToArrayTrait;

/**
 * ServiceSummary, child of
 *
 * @see \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails
 *
 * @api Will be serialized in persistent storage, any change needs a migration.
 */
final class ResultSummary implements ToArrayInterface {

  use ToArrayTrait;

  public function __construct(
    public readonly ?string                 $serviceName,
    public readonly ResultSummaryMatchLevel $matchLevel,
    public readonly ListInfo                $listInfo
  ) {}

}

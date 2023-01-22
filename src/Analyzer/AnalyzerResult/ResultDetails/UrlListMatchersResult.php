<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\Arrayable\ToArrayInterface;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\Arrayable\ToArrayTrait;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\TestSummary\TestSummaryInterface;

/**
 * Url list matching summary (links, images); child of
 *
 * @see \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\LinkAndImageUrlListMatcherResult
 *
 * @api Will be serialized in persistent storage, any change needs a migration.
 */
final class UrlListMatchersResult implements TestSummaryInterface, ToArrayInterface {

  use ToArrayTrait;

  /**
   * @param \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\UrlListPerServiceMatchesList $perServiceResultList
   */
  public function __construct(
    public readonly UrlListPerServiceMatchesList $perServiceResultList
  ) {}

  /**
   * @return \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\UrlListPerServiceMatchesList
   */
  public function getPerServiceResultList(): UrlListPerServiceMatchesList {
    return $this->perServiceResultList;
  }


  public function getTestSummary(): array {
    return [
      'perServiceResultList' => $this->perServiceResultList->getTestSummary(),
    ];
  }

}

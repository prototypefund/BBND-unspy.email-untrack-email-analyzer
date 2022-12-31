<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\TestSummary\TestSummaryInterface;

/**
 * Url list matching summary (links, images); child of
 *
 * @see \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\LinkAndImageUrlListMatcherResult
 *
 * @api Will be serialized in persistent storage, any change needs a migration.
 */
final class UrlListMatchersResult implements TestSummaryInterface {

  protected UrlListPerServiceMatchesList $perServiceResultList;

  /**
   * @param \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\UrlListPerServiceMatchesList $perServiceResultList
   */
  public function __construct(UrlListPerServiceMatchesList $perServiceResultList) {
    // This is a trivial container on purpose, for future flexibility.
    $this->perServiceResultList = $perServiceResultList;
  }

  /**
   * @return \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\UrlListPerServiceMatchesList
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

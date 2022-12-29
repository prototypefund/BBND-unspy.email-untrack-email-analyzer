<?php

declare(strict_types=1);

namespace Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult;

use Geeks4change\BbndAnalyzer\Analyzer\TestSummary\TestSummaryInterface;

/**
 * Url list matching summary (links, images); child of
 * @see \Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult\LinkAndImageUrlListMatcherResult
 *
 * @api Will be serialized in persistent storage, any change needs a migration.
 */
final class UrlListMatchersResult implements TestSummaryInterface {

  protected UrlListPerServiceMatchesList $perServiceResultList;

  /**
   * @param \Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult\UrlListPerServiceMatchesList $perServiceResultList
   */
  public function __construct(UrlListPerServiceMatchesList $perServiceResultList) {
    // This is a trivial container on purpose, for future flexibility.
    $this->perServiceResultList = $perServiceResultList;
  }

  /**
   * @return \Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult\UrlListPerServiceMatchesList
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

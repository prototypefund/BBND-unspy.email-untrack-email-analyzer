<?php

declare(strict_types=1);

namespace Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult;

use Geeks4change\BbndAnalyzer\TestHelpers\TestSummaryInterface;

/**
 * Url list matching summary (links, images); child of
 *
 * @see \Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult\LinkAndImageUrlListMatcherResult
 *
 * @api Will be serialized in persistent storage, any change needs a migration.
 */
final class UrlListMatchersResult implements TestSummaryInterface {

  protected UrlListPerServiceMatchesList $perServiceResultList;

  protected UrlList $noMatchList;

  /**
   * @param \Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult\UrlListPerServiceMatchesList $perServiceResultList
   * @param \Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult\UrlList $noMatchList
   */
  public function __construct(UrlListPerServiceMatchesList $perServiceResultList, UrlList $noMatchList) {
    $this->perServiceResultList = $perServiceResultList;
    $this->noMatchList = $noMatchList;
  }


  /**
   * @return \Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult\UrlList
   */
  public function getNoMatchList(): UrlList {
    return $this->noMatchList;
  }

  /**
   * @return \Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult\UrlListPerServiceMatchesList
   */
  public function getPerServiceResultList(): UrlListPerServiceMatchesList {
    return $this->perServiceResultList;
  }


  public function getTestSummary(): array {
    return [
      'noMatchList' => $this->noMatchList->getTestSummary(),
      'perServiceResultList' => $this->perServiceResultList->getTestSummary(),
    ];
  }

}

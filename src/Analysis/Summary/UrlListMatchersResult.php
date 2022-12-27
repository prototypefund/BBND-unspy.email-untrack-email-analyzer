<?php

declare(strict_types=1);

namespace Geeks4change\BbndAnalyzer\Analysis\Summary;

/**
 * Url list matching summary (links, images); child of
 * @see \Geeks4change\BbndAnalyzer\Analysis\Summary\LinkAndImageUrlListMatcherResult
 *
 * @api Will be serialized in persistent storage, any change needs a migration.
 */
final class UrlListMatchersResult {

  protected UrlListPerServiceMatchesList $perServiceResultList;

  protected UrlList $noMatchList;

  protected UrlList $hasRedirectList;

  protected UrlList $hasAnalyticsList;

  /**
   * @param \Geeks4change\BbndAnalyzer\Analysis\Summary\UrlListPerServiceMatchesList $perServiceResultList
   * @param \Geeks4change\BbndAnalyzer\Analysis\Summary\UrlList $noMatchList
   * @param \Geeks4change\BbndAnalyzer\Analysis\Summary\UrlList $hasRedirectList
   * @param \Geeks4change\BbndAnalyzer\Analysis\Summary\UrlList $hasAnalyticsList
   */
  public function __construct(UrlListPerServiceMatchesList $perServiceResultList, UrlList $noMatchList, UrlList $hasRedirectList, UrlList $hasAnalyticsList) {
    $this->noMatchList = $noMatchList;
    $this->hasAnalyticsList = $hasAnalyticsList;
    $this->hasRedirectList = $hasRedirectList;
    $this->perServiceResultList = $perServiceResultList;
  }

  /**
   * @return \Geeks4change\BbndAnalyzer\Analysis\Summary\UrlList
   */
  public function getNoMatchList(): UrlList {
    return $this->noMatchList;
  }

  /**
   * @return \Geeks4change\BbndAnalyzer\Analysis\Summary\UrlList
   */
  public function getHasAnalyticsList(): UrlList {
    return $this->hasAnalyticsList;
  }

  /**
   * @return \Geeks4change\BbndAnalyzer\Analysis\Summary\UrlList
   */
  public function getHasRedirectList(): UrlList {
    return $this->hasRedirectList;
  }

  /**
   * @return \Geeks4change\BbndAnalyzer\Analysis\Summary\UrlListPerServiceMatchesList
   */
  public function getPerServiceResultList(): UrlListPerServiceMatchesList {
    return $this->perServiceResultList;
  }


}

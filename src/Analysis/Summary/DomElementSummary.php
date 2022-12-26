<?php

declare(strict_types=1);

namespace Geeks4change\BbndAnalyzer\Analysis\Summary;

/**
 * @api Will be serialized in persistent storage, any change needs a migration.
 */
final class DomElementSummary {

  /**
   * @var array<\Geeks4change\BbndAnalyzer\Analysis\Summary\UrlSummary>
   */
  protected array $noMatchList;

  /**
   * @var array<\Geeks4change\BbndAnalyzer\Analysis\Summary\UrlSummary>
   */
  protected array $hasAnalyticsList;

  /**
   * @var array<\Geeks4change\BbndAnalyzer\Analysis\Summary\UrlSummary>
   */
  protected array $hasRedirectList;

  /**
   * @var array<DomElementPerServiceSummary>
   */
  protected array $perServiceSummaryList;

  /**
   * @param \Geeks4change\BbndAnalyzer\Analysis\Summary\UrlSummary[] $noMatchList
   * @param \Geeks4change\BbndAnalyzer\Analysis\Summary\UrlSummary[] $hasAnalyticsList
   * @param \Geeks4change\BbndAnalyzer\Analysis\Summary\UrlSummary[] $hasRedirectList
   * @param \Geeks4change\BbndAnalyzer\Analysis\Summary\DomElementPerServiceSummary[] $perServiceSummaryList
   */
  public function __construct(array $noMatchList, array $hasAnalyticsList, array $hasRedirectList, array $perServiceSummaryList) {
    $this->noMatchList = $noMatchList;
    $this->hasAnalyticsList = $hasAnalyticsList;
    $this->hasRedirectList = $hasRedirectList;
    $this->perServiceSummaryList = $perServiceSummaryList;
  }

  /**
   * @return array
   */
  public function getNoMatchList(): array {
    return $this->noMatchList;
  }

  /**
   * @return array
   */
  public function getHasAnalyticsList(): array {
    return $this->hasAnalyticsList;
  }

  /**
   * @return array
   */
  public function getHasRedirectList(): array {
    return $this->hasRedirectList;
  }

  /**
   * @return array
   */
  public function getPerServiceSummaryList(): array {
    return $this->perServiceSummaryList;
  }


}

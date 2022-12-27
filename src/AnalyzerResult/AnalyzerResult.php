<?php

declare(strict_types=1);
namespace Geeks4change\BbndAnalyzer\AnalyzerResult;

/**
 * Analysis summary.
 *
 * @api Will be serialized in persistent storage, any change needs a migration.
 */
final class AnalyzerResult {

  protected AggregatedSummary $aggregated;

  protected MayNeedResearch $mayNeedResearch;

  protected DKIMResult $dkimResult;

  protected HeaderResult $headersResult;

  protected LinkAndImageUrlListMatcherResult  $linkAndImageUrlsResult;

  protected UrlList $pixelsResult;

  protected DomainAliasesList $domainAliasesList;

  /**
   * @param \Geeks4change\BbndAnalyzer\AnalyzerResult\AggregatedSummary $aggregated
   * @param \Geeks4change\BbndAnalyzer\AnalyzerResult\MayNeedResearch $mayNeedResearch
   * @param \Geeks4change\BbndAnalyzer\AnalyzerResult\DKIMResult $dkimResult
   * @param \Geeks4change\BbndAnalyzer\AnalyzerResult\HeaderResult $headersResult
   * @param \Geeks4change\BbndAnalyzer\AnalyzerResult\LinkAndImageUrlListMatcherResult $linkAndImageUrlsResult
   * @param \Geeks4change\BbndAnalyzer\AnalyzerResult\UrlList $pixelsResult
   * @param \Geeks4change\BbndAnalyzer\AnalyzerResult\DomainAliasesList $domainAliasesList
   */
  public function __construct(AggregatedSummary $aggregated, MayNeedResearch $mayNeedResearch, DKIMResult $dkimResult, HeaderResult $headersResult, LinkAndImageUrlListMatcherResult $linkAndImageUrlsResult, UrlList $pixelsResult, DomainAliasesList $domainAliasesList) {
    $this->aggregated = $aggregated;
    $this->mayNeedResearch = $mayNeedResearch;
    $this->dkimResult = $dkimResult;
    $this->headersResult = $headersResult;
    $this->linkAndImageUrlsResult = $linkAndImageUrlsResult;
    $this->pixelsResult = $pixelsResult;
    $this->domainAliasesList = $domainAliasesList;
  }

  /**
   * @return \Geeks4change\BbndAnalyzer\AnalyzerResult\AggregatedSummary
   */
  public function getAggregated(): AggregatedSummary {
    return $this->aggregated;
  }

  /**
   * @return \Geeks4change\BbndAnalyzer\AnalyzerResult\MayNeedResearch
   */
  public function getMayNeedResearch(): MayNeedResearch {
    return $this->mayNeedResearch;
  }

  /**
   * @return \Geeks4change\BbndAnalyzer\AnalyzerResult\DKIMResult
   */
  public function getDkimResult(): DKIMResult {
    return $this->dkimResult;
  }

  /**
   * @return \Geeks4change\BbndAnalyzer\AnalyzerResult\HeaderResult
   */
  public function getHeadersResult(): HeaderResult {
    return $this->headersResult;
  }

  /**
   * @return \Geeks4change\BbndAnalyzer\AnalyzerResult\LinkAndImageUrlListMatcherResult
   */
  public function getLinkAndImageUrlsResult(): LinkAndImageUrlListMatcherResult {
    return $this->linkAndImageUrlsResult;
  }

  /**
   * @return \Geeks4change\BbndAnalyzer\AnalyzerResult\UrlList
   */
  public function getPixelsResult(): UrlList {
    return $this->pixelsResult;
  }

  /**
   * @return \Geeks4change\BbndAnalyzer\AnalyzerResult\DomainAliasesList
   */
  public function getDomainAliasesList(): DomainAliasesList {
    return $this->domainAliasesList;
  }


}

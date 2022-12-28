<?php

declare(strict_types=1);
namespace Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult;

use Geeks4change\BbndAnalyzer\Analyzer\TestSummary\TestSummaryInterface;

/**
 * Analysis summary.
 *
 * @api Will be serialized in persistent storage, any change needs a migration.
 */
final class AnalyzerResult implements TestSummaryInterface {

  protected AggregatedSummary $aggregated;

  protected MayNeedResearch $mayNeedResearch;

  protected DKIMResult $dkimResult;

  protected HeadersResult $headersResult;

  protected LinkAndImageUrlListMatcherResult  $linkAndImageUrlsResult;

  protected UrlList $pixelsList;

  protected UrlList $urlsWithRedirectList;

  protected UrlList $urlsWithAnalyticsList;

  protected DomainAliasesList $domainAliasesList;

  /**
   * @param \Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult\AggregatedSummary $aggregated
   * @param \Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult\MayNeedResearch $mayNeedResearch
   * @param \Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult\DKIMResult $dkimResult
   * @param \Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult\HeadersResult $headersResult
   * @param \Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult\LinkAndImageUrlListMatcherResult $linkAndImageUrlsResult
   * @param \Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult\UrlList $pixelsList
   * @param \Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult\UrlList $urlsWithRedirectList
   * @param \Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult\UrlList $urlsWithAnalyticsList
   * @param \Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult\DomainAliasesList $domainAliasesList
   */
  public function __construct(AggregatedSummary $aggregated, MayNeedResearch $mayNeedResearch, DKIMResult $dkimResult, HeadersResult $headersResult, LinkAndImageUrlListMatcherResult $linkAndImageUrlsResult, UrlList $pixelsList, UrlList $urlsWithRedirectList, UrlList $urlsWithAnalyticsList, DomainAliasesList $domainAliasesList) {
    $this->aggregated = $aggregated;
    $this->mayNeedResearch = $mayNeedResearch;
    $this->dkimResult = $dkimResult;
    $this->headersResult = $headersResult;
    $this->linkAndImageUrlsResult = $linkAndImageUrlsResult;
    $this->pixelsList = $pixelsList;
    $this->urlsWithRedirectList = $urlsWithRedirectList;
    $this->urlsWithAnalyticsList = $urlsWithAnalyticsList;
    $this->domainAliasesList = $domainAliasesList;
  }

  /**
   * @return \Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult\AggregatedSummary
   */
  public function getAggregated(): AggregatedSummary {
    return $this->aggregated;
  }

  /**
   * @return \Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult\MayNeedResearch
   */
  public function getMayNeedResearch(): MayNeedResearch {
    return $this->mayNeedResearch;
  }

  /**
   * @return \Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult\DKIMResult
   */
  public function getDkimResult(): DKIMResult {
    return $this->dkimResult;
  }

  /**
   * @return \Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult\HeadersResult
   */
  public function getHeadersResult(): HeadersResult {
    return $this->headersResult;
  }

  /**
   * @return \Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult\LinkAndImageUrlListMatcherResult
   */
  public function getLinkAndImageUrlsResult(): LinkAndImageUrlListMatcherResult {
    return $this->linkAndImageUrlsResult;
  }

  /**
   * @return \Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult\UrlList
   */
  public function getPixelsList(): UrlList {
    return $this->pixelsList;
  }

  /**
   * @return \Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult\UrlList
   */
  public function getUrlsWithRedirectList(): UrlList {
    return $this->urlsWithRedirectList;
  }

  /**
   * @return \Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult\UrlList
   */
  public function getUrlsWithAnalyticsList(): UrlList {
    return $this->urlsWithAnalyticsList;
  }

  /**
   * @return \Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult\DomainAliasesList
   */
  public function getDomainAliasesList(): DomainAliasesList {
    return $this->domainAliasesList;
  }

  public function getTestSummary(): array {
    return [
      'headersResult' => $this->headersResult->getTestSummary(),
      'linkAndImageUrlsResult' => $this->linkAndImageUrlsResult->getTestSummary(),
      'urlsWithRedirectList' => $this->urlsWithRedirectList->getTestSummary(),
      'urlsWithAnalyticsList' => $this->urlsWithAnalyticsList->getTestSummary(),
    ];
  }

}

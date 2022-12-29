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

  protected LinkAndImageUrlList $allLinkAndImageUrlsList;

  protected LinkAndImageUrlListMatcherResult  $linkAndImageUrlsResult;

  protected UrlList $pixelsList;

  protected LinkAndImageRedirectInfoList $urlsRedirectInfoList;

  protected LinkAndImageUrlList $urlsWithAnalyticsList;

  protected DomainAliasesList $domainAliasesList;

  /**
   * @param \Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult\AggregatedSummary $aggregated
   * @param \Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult\MayNeedResearch $mayNeedResearch
   * @param \Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult\DKIMResult $dkimResult
   * @param \Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult\HeadersResult $headersResult
   * @param \Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult\LinkAndImageUrlList $allLinkAndImageUrlsList
   * @param \Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult\LinkAndImageUrlListMatcherResult $linkAndImageUrlsResult
   * @param \Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult\UrlList $pixelsList
   * @param \Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult\LinkAndImageRedirectInfoList $urlsRedirectInfoList
   * @param \Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult\LinkAndImageUrlList $urlsWithAnalyticsList
   * @param \Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult\DomainAliasesList $domainAliasesList
   */
  public function __construct(AggregatedSummary $aggregated, MayNeedResearch $mayNeedResearch, DKIMResult $dkimResult, HeadersResult $headersResult, LinkAndImageUrlList $allLinkAndImageUrlsList, LinkAndImageUrlListMatcherResult $linkAndImageUrlsResult, UrlList $pixelsList, LinkAndImageRedirectInfoList $urlsRedirectInfoList, LinkAndImageUrlList $urlsWithAnalyticsList, DomainAliasesList $domainAliasesList) {
    $this->aggregated = $aggregated;
    $this->mayNeedResearch = $mayNeedResearch;
    $this->dkimResult = $dkimResult;
    $this->headersResult = $headersResult;
    $this->allLinkAndImageUrlsList = $allLinkAndImageUrlsList;
    $this->linkAndImageUrlsResult = $linkAndImageUrlsResult;
    $this->pixelsList = $pixelsList;
    $this->urlsRedirectInfoList = $urlsRedirectInfoList;
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
   * @return \Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult\LinkAndImageUrlList
   */
  public function getAllLinkAndImageUrlsList(): LinkAndImageUrlList {
    return $this->allLinkAndImageUrlsList;
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
   * @return \Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult\LinkAndImageRedirectInfoList
   */
  public function getUrlsRedirectInfoList(): LinkAndImageRedirectInfoList {
    return $this->urlsRedirectInfoList;
  }

  /**
   * @return \Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult\LinkAndImageUrlList
   */
  public function getUrlsWithAnalyticsList(): LinkAndImageUrlList {
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
      'allLinkAndImageUrlsList' => $this->allLinkAndImageUrlsList->getTestSummary(),
      'linkAndImageUrlsResult' => $this->linkAndImageUrlsResult->getTestSummary(),
      'pixelsList' => $this->pixelsList->getTestSummary(),
      'urlsWithRedirectList' => $this->urlsRedirectInfoList->getTestSummary(),
      'urlsWithAnalyticsList' => $this->urlsWithAnalyticsList->getTestSummary(),
    ];
  }

}

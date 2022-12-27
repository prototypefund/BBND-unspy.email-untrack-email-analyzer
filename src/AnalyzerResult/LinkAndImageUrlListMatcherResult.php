<?php

declare(strict_types=1);

namespace Geeks4change\BbndAnalyzer\AnalyzerResult;

/**
 * LinkAndImageUrlsMatcherResult, child of
 *
 * @see \Geeks4change\BbndAnalyzer\AnalyzerResult\AnalyzerResult
 */
final class LinkAndImageUrlListMatcherResult {

  protected UrlListMatchersResult $linkUrlsResult;

  protected UrlListMatchersResult $imageUrlsResult;

  /**
   * @param \Geeks4change\BbndAnalyzer\AnalyzerResult\UrlListMatchersResult $linkUrlsResult
   * @param \Geeks4change\BbndAnalyzer\AnalyzerResult\UrlListMatchersResult $imageUrlsResult
   */
  public function __construct(UrlListMatchersResult $linkUrlsResult, UrlListMatchersResult $imageUrlsResult) {
    $this->linkUrlsResult = $linkUrlsResult;
    $this->imageUrlsResult = $imageUrlsResult;
  }

  /**
   * @return \Geeks4change\BbndAnalyzer\AnalyzerResult\UrlListMatchersResult
   */
  public function getLinkUrlsResult(): UrlListMatchersResult {
    return $this->linkUrlsResult;
  }

  /**
   * @return \Geeks4change\BbndAnalyzer\AnalyzerResult\UrlListMatchersResult
   */
  public function getImageUrlsResult(): UrlListMatchersResult {
    return $this->imageUrlsResult;
  }

}

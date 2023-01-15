<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\Arrayable\ToArrayInterface;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\Arrayable\ToArrayTrait;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\TestSummary\TestSummaryInterface;

/**
 * LinkAndImageUrlsMatcherResult, child of
 *
 * @see \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails
 */
final class LinkAndImageUrlListMatcherResult implements TestSummaryInterface, ToArrayInterface {

  use ToArrayTrait;

  protected UrlListMatchersResult $linkUrlsResult;

  protected UrlListMatchersResult $imageUrlsResult;

  /**
   * @param \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\UrlListMatchersResult $linkUrlsResult
   * @param \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\UrlListMatchersResult $imageUrlsResult
   */
  public function __construct(UrlListMatchersResult $linkUrlsResult, UrlListMatchersResult $imageUrlsResult) {
    $this->linkUrlsResult = $linkUrlsResult;
    $this->imageUrlsResult = $imageUrlsResult;
  }

  /**
   * @return \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\UrlListMatchersResult
   */
  public function getLinkUrlsResult(): UrlListMatchersResult {
    return $this->linkUrlsResult;
  }

  /**
   * @return \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\UrlListMatchersResult
   */
  public function getImageUrlsResult(): UrlListMatchersResult {
    return $this->imageUrlsResult;
  }

  public function getTestSummary(): array {
    return [
      'linkUrlsResult' => $this->linkUrlsResult->getTestSummary(),
      'imageUrlsResult' => $this->imageUrlsResult->getTestSummary(),
    ];
  }

}

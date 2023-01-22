<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\Arrayable\ToArrayInterface;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\Arrayable\ToArrayTrait;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\TestSummary\TestSummaryInterface;

/**
 * LinkAndImageUrlsMatcherResult, child of
 *
 * @see \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\ResultDetails
 */
final class LinkAndImageUrlListMatcherResult implements TestSummaryInterface, ToArrayInterface {

  use ToArrayTrait;

  public function __construct(
    public readonly UrlListMatchersResult $linkUrlsResult,
    public readonly UrlListMatchersResult $imageUrlsResult
  ) {
  }

  public function getTestSummary(): array {
    return [
      'linkUrlsResult' => $this->linkUrlsResult->getTestSummary(),
      'imageUrlsResult' => $this->imageUrlsResult->getTestSummary(),
    ];
  }

}

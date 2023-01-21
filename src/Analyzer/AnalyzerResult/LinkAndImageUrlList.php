<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\Arrayable\ToArrayInterface;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\Arrayable\ToArrayTrait;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\TestSummary\TestSummaryInterface;

/**
 * Intermediary result.
 */
final class LinkAndImageUrlList implements TestSummaryInterface, ToArrayInterface {

  use ToArrayTrait;

  /**
   * @param \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\UrlList $linkUrlList
   * @param \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\UrlList $imageUrlList
   */
  public function __construct(
    public readonly UrlList $linkUrlList,
    public readonly UrlList $imageUrlList
  ) {}

  public function getTestSummary(): array {
    return [
      'linkUrlList' => $this->linkUrlList->getTestSummary(),
      'imageUrlList' => $this->imageUrlList->getTestSummary(),
    ];
  }

}

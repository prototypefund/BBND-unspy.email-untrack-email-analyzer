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

  protected UrlList $linkUrlList;

  protected UrlList $imageUrlList;

  /**
   * @param \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\UrlList $linkUrlList
   * @param \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\UrlList $imageUrlList
   */
  public function __construct(UrlList $linkUrlList, UrlList $imageUrlList) {
    $this->linkUrlList = $linkUrlList;
    $this->imageUrlList = $imageUrlList;
  }

  /**
   * @return \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\UrlList
   */
  public function getLinkUrlList(): UrlList {
    return $this->linkUrlList;
  }

  /**
   * @return \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\UrlList
   */
  public function getImageUrlList(): UrlList {
    return $this->imageUrlList;
  }

  public function getTestSummary(): array {
    return [
      'linkUrlList' => $this->linkUrlList->getTestSummary(),
      'imageUrlList' => $this->imageUrlList->getTestSummary(),
    ];
  }

}

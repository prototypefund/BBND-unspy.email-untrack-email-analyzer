<?php

declare(strict_types=1);

namespace Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult;

use Geeks4change\BbndAnalyzer\Analyzer\TestSummary\TestSummaryInterface;

final class LinkAndImageRedirectInfoList  implements TestSummaryInterface {

  protected UrlRedirectInfoList $linkRedirectInfoList;

  protected UrlRedirectInfoList $imageRedirectInfoList;

  /**
   * @param \Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult\UrlRedirectInfoList $linkRedirectInfoList
   * @param \Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult\UrlRedirectInfoList $imageRedirectInfoList
   */
  public function __construct(UrlRedirectInfoList $linkRedirectInfoList, UrlRedirectInfoList $imageRedirectInfoList) {
    $this->linkRedirectInfoList = $linkRedirectInfoList;
    $this->imageRedirectInfoList = $imageRedirectInfoList;
  }

  /**
   * @return \Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult\UrlRedirectInfoList
   */
  public function getLinkRedirectInfoList(): UrlRedirectInfoList {
    return $this->linkRedirectInfoList;
  }

  /**
   * @return \Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult\UrlRedirectInfoList
   */
  public function getImageRedirectInfoList(): UrlRedirectInfoList {
    return $this->imageRedirectInfoList;
  }


  public function getTestSummary(): array {
    return [
      'linkUrlList' => $this->linkRedirectInfoList->getTestSummary(),
      'imageUrlList' => $this->imageRedirectInfoList->getTestSummary(),
    ];
  }

}
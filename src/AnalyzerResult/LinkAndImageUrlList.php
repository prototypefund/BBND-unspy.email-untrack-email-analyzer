<?php

declare(strict_types=1);

namespace Geeks4change\BbndAnalyzer\AnalyzerResult;

/**
 * Intermediary result.
 */
final class LinkAndImageUrlList {

  protected UrlList $linkUrlList;

  protected UrlList $imageUrlList;

  /**
   * @param \Geeks4change\BbndAnalyzer\AnalyzerResult\UrlList $linkUrlList
   * @param \Geeks4change\BbndAnalyzer\AnalyzerResult\UrlList $imageUrlList
   */
  public function __construct(UrlList $linkUrlList, UrlList $imageUrlList) {
    $this->linkUrlList = $linkUrlList;
    $this->imageUrlList = $imageUrlList;
  }

  /**
   * @return \Geeks4change\BbndAnalyzer\AnalyzerResult\UrlList
   */
  public function getLinkUrlList(): UrlList {
    return $this->linkUrlList;
  }

  /**
   * @return \Geeks4change\BbndAnalyzer\AnalyzerResult\UrlList
   */
  public function getImageUrlList(): UrlList {
    return $this->imageUrlList;
  }

}

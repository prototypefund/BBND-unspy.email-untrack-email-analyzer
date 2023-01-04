<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\LinkAndImageRedirectInfoList;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\LinkAndImageUrlList;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\UrlList;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\UrlRedirectInfoList;
use Geeks4change\UntrackEmailAnalyzer\RedirectResolver\AsyncGuzzleRedirectResolver;

final class RedirectDetector {

  public function detectRedirect(LinkAndImageUrlList $linkAndImageUrlList, UrlList $excludeUrlList): LinkAndImageRedirectInfoList {
    $urlList = $this->combineUrls($linkAndImageUrlList);

    $redirectResolver = new AsyncGuzzleRedirectResolver();
    $urlRedirectInfoList = $redirectResolver->resolveRedirects($urlList, $excludeUrlList);

    return new LinkAndImageRedirectInfoList(
      $this->assignRedirects($linkAndImageUrlList->getLinkUrlList(), $urlRedirectInfoList),
      $this->assignRedirects($linkAndImageUrlList->getImageUrlList(), $urlRedirectInfoList),
    );
  }

  /**
   * @param \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\LinkAndImageUrlList $linkAndImageUrlList
   *
   * @return \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\UrlList
   */
  protected function combineUrls(LinkAndImageUrlList $linkAndImageUrlList): UrlList {
    $combinedUrlList = new UrlList();
    /** @var UrlList $urlList */
    foreach ([
               $linkAndImageUrlList->getLinkUrlList(),
               $linkAndImageUrlList->getImageUrlList(),
             ] as $urlList) {
      /** @var \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\Url $urlItem */
      foreach ($urlList as $urlItem) {
        $combinedUrlList->add($urlItem->toString());
      }
    }
    return $combinedUrlList;
  }

  protected function assignRedirects(UrlList $urlList, UrlRedirectInfoList $allUrlRedirectInfoList): UrlRedirectInfoList {
    $urlRedirectInfoList = new UrlRedirectInfoList();
    foreach ($urlList as $urlItem) {
      if ($redirectInfo = $allUrlRedirectInfoList->get($urlItem->toString())) {
        $urlRedirectInfoList->add($redirectInfo);
      }
    }
    return $urlRedirectInfoList;
  }

}

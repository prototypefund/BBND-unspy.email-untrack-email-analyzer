<?php

declare(strict_types=1);

namespace Geeks4change\BbndAnalyzer\Analyzer;

use Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult\LinkAndImageRedirectInfoList;
use Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult\LinkAndImageUrlList;
use Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult\UrlList;
use Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult\UrlRedirectInfoList;
use Geeks4change\BbndAnalyzer\RedirectResolver;

final class RedirectDetector {

  public function detectRedirect(LinkAndImageUrlList $linkAndImageUrlList, UrlList $excludeUrlList): LinkAndImageRedirectInfoList {
    return new LinkAndImageRedirectInfoList(
      $this->doDetectRedirect($linkAndImageUrlList->getLinkUrlList(), $excludeUrlList),
      $this->doDetectRedirect($linkAndImageUrlList->getImageUrlList(), $excludeUrlList),
    );
  }

  protected function doDetectRedirect(UrlList $urlList, UrlList $excludeUrlList): UrlRedirectInfoList {
    $redirectResolver = new RedirectResolver();
    $urlRedirectInfoList = new UrlRedirectInfoList();
    foreach ($urlList as $urlWrapper) {
      $url = strval($urlWrapper);
      if (!$excludeUrlList->contains($url)) {
        $redirectInfo = $redirectResolver->resolveRedirect($url);
        if ($redirectInfo->hasRedirect()) {
          $urlRedirectInfoList->add($redirectInfo);
        }
      }
    }
    return $urlRedirectInfoList;
  }
}

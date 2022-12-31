<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\LinkAndImageRedirectInfoList;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\LinkAndImageUrlList;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\UrlList;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\UrlRedirectInfoList;
use Geeks4change\UntrackEmailAnalyzer\RedirectResolver;

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

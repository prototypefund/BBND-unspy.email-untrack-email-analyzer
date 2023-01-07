<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\LinkAndImageRedirectInfoList;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\LinkAndImageUrlList;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\UrlList;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\UrlRedirectInfoList;
use Geeks4change\UntrackEmailAnalyzer\RedirectResolver\AsyncGuzzleRedirectResolver;
use Geeks4change\UntrackEmailAnalyzer\RedirectResolver\AsyncPhpClientRedirectResolver;
use Geeks4change\UntrackEmailAnalyzer\RedirectResolver\RedirectResolverInterface;

final class RedirectDetector {

  protected RedirectResolverInterface $redirectResolver;

  public function __construct() {
    // Use guzzle instead of symfony client, as the other seems to trigger bot
    // service denial for rapidmail. Dunno why.
    $this->redirectResolver = new AsyncGuzzleRedirectResolver();
  }

  public function detectRedirect(LinkAndImageUrlList $linkAndImageUrlList, UrlList $urlsToExclude): LinkAndImageRedirectInfoList {
    $urlList = $this->combineUrls($linkAndImageUrlList, $urlsToExclude);

    $urlRedirectInfoList = $this->redirectResolver->resolveRedirects($urlList);

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
  protected function combineUrls(LinkAndImageUrlList $linkAndImageUrlList, UrlList $urlsToExclude): UrlList {
    $combinedUrlList = new UrlList();
    /** @var UrlList $urlList */
    foreach ([
               $linkAndImageUrlList->getLinkUrlList(),
               $linkAndImageUrlList->getImageUrlList(),
             ] as $urlList) {
      /** @var \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\UrlItem $urlItem */
      foreach ($urlList as $urlItem) {
        $url = $urlItem->toString();
        if (!$urlsToExclude->contains($url)) {
          $combinedUrlList->add($url);
        }
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

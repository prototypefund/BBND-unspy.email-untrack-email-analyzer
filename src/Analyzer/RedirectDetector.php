<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\LinkAndImageRedirectInfoList;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\LinkAndImageUrlList;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\UrlList;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\UrlRedirectInfoList;
use Geeks4change\UntrackEmailAnalyzer\RedirectResolver\AsyncGuzzleRedirectResolver;
use Geeks4change\UntrackEmailAnalyzer\RedirectResolver\RedirectResolverInterface;

final class RedirectDetector implements RedirectDetectorInterface {

  protected RedirectResolverInterface $redirectResolver;

  public function __construct(RedirectResolverInterface $redirectResolver = NULL) {
    // Use guzzle instead of symfony client, as the other seems to trigger bot
    // service denial for rapidmail. Dunno why.
    $this->redirectResolver = $redirectResolver ?? new AsyncGuzzleRedirectResolver();
  }

  public function detectRedirect(LinkAndImageUrlList $linkAndImageUrlList, UrlList $urlsToExclude): LinkAndImageRedirectInfoList {
    $urlList = $this->combineUrls($linkAndImageUrlList, $urlsToExclude);

    $urlRedirectInfoList = $this->redirectResolver->resolveRedirects($urlList);

    return new LinkAndImageRedirectInfoList(
      $this->assignRedirects($linkAndImageUrlList->linkUrlList, $urlRedirectInfoList),
      $this->assignRedirects($linkAndImageUrlList->imageUrlList, $urlRedirectInfoList),
    );
  }

  /**
   * @param \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\LinkAndImageUrlList $linkAndImageUrlList
   *
   * @return \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\UrlList
   */
  protected function combineUrls(LinkAndImageUrlList $linkAndImageUrlList, UrlList $urlsToExclude): UrlList {
    $combinedUrlList = UrlList::builder();
    /** @var UrlList $urlList */
    foreach ([
               $linkAndImageUrlList->linkUrlList,
               $linkAndImageUrlList->imageUrlList,
             ] as $urlList) {
      /** @var \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\UrlItem $urlItem */
      foreach ($urlList as $urlItem) {
        $url = $urlItem->toString();
        if (!$urlsToExclude->contains($url)) {
          $combinedUrlList->add($url);
        }
      }
    }
    return $combinedUrlList->freeze();
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

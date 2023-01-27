<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\TypedUrlRedirectChainList;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\TypedUrlList;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\UrlList;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\UrlRedirectChainList;
use Geeks4change\UntrackEmailAnalyzer\RedirectResolver\AsyncGuzzleRedirectResolver;
use Geeks4change\UntrackEmailAnalyzer\RedirectResolver\RedirectResolverInterface;

final class RedirectDetector implements RedirectDetectorInterface {

  protected RedirectResolverInterface $redirectResolver;

  public function __construct(RedirectResolverInterface $redirectResolver = NULL) {
    // Use guzzle instead of symfony client, as the other seems to trigger bot
    // service denial for rapidmail. Dunno why.
    $this->redirectResolver = $redirectResolver ?? new AsyncGuzzleRedirectResolver();
  }

  public function detectRedirect(TypedUrlList $linkAndImageUrlList, UrlList $urlsToExclude): TypedUrlRedirectChainList {
    // Combine urls to resolve redirects async.
    $urlList = $this->combineUrls($linkAndImageUrlList, $urlsToExclude);
    $urlRedirectInfoList = $this->redirectResolver->resolveRedirects($urlList);
    return new TypedUrlRedirectChainList(
      $this->assignRedirects($linkAndImageUrlList->typeLink, $urlRedirectInfoList),
      $this->assignRedirects($linkAndImageUrlList->typeImage, $urlRedirectInfoList),
    );
  }

  /**
   * @param \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\TypedUrlList $linkAndImageUrlList
   *
   * @return \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\UrlList
   */
  protected function combineUrls(TypedUrlList $linkAndImageUrlList, UrlList $urlsToExclude): UrlList {
    $combinedUrlList = UrlList::builder();
    /** @var UrlList $urlList */
    foreach ([
               $linkAndImageUrlList->typeLink,
               $linkAndImageUrlList->typeImage,
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

  protected function assignRedirects(UrlList $urlList, UrlRedirectChainList $allUrlRedirectInfoList): UrlRedirectChainList {
    $urlRedirectInfoList = UrlRedirectChainList::builder();
    foreach ($urlList as $urlItem) {
      if ($redirectInfo = $allUrlRedirectInfoList->get($urlItem->toString())) {
        $urlRedirectInfoList->add($redirectInfo);
      }
    }
    return $urlRedirectInfoList->freeze();
  }

}

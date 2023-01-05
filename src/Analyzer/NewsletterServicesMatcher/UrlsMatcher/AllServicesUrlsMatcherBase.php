<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\NewsletterServicesMatcher\UrlsMatcher;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\UrlList;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\UrlListMatchersResult;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\UrlListPerServiceMatches;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\UrlListPerServiceMatchesList;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\NewsletterServicesMatcher\ServiceMatcherProvider;
use Geeks4change\UntrackEmailAnalyzer\Globals;
use Psr\Http\Message\UriInterface;

abstract class AllServicesUrlsMatcherBase {

  public function generateUrlListResult(UrlList $urlList): UrlListMatchersResult {
    $perServiceResultList = new UrlListPerServiceMatchesList();
    /** @var \Geeks4change\UntrackEmailAnalyzer\Analyzer\NewsletterServicesMatcher\ServiceMatcherProvider $toolPattern */
    foreach (Globals::get()->getServiceMatcherProviderRepository()->getServiceMatcherProviderCollection() as $toolPattern) {
      $matchedExactly = new UrlList();
      $matchedByDomain = new UrlList();
      $notMatched = new UrlList();
      foreach ($urlList as $url) {
        if ($this->isUrlPatternMatch($toolPattern, $url->getUrlObject())) {
          $matchedExactly->add(strval($url));
        }
        elseif ($this->isDomainPatternMatch($toolPattern, $url->getUrlObject())) {
          $matchedByDomain->add(strval($url));
        }
        else {
          $notMatched->add(strval($url));
        }
      }
      $perServiceMatches = new UrlListPerServiceMatches(
        $toolPattern->getName(),
        $matchedExactly,
        $matchedByDomain,
        $notMatched,
      );
      if ($perServiceMatches->isNonEmpty()) {
        $perServiceResultList->add($perServiceMatches);
      }
    }
    return new UrlListMatchersResult($perServiceResultList);
}

  protected function isUrlPatternMatch(ServiceMatcherProvider $toolPattern, UriInterface $url): bool {
    $linkPatternMatch = FALSE;
    // @fixme Move to matcher method.
    foreach ($this->getUrlPatterns($toolPattern) as $urlPattern) {
      if ($urlPattern->match($url)) {
        $linkPatternMatch = TRUE;
      }
    }
    return $linkPatternMatch;
  }

  /**
   * @return \Geeks4change\UntrackEmailAnalyzer\Analyzer\NewsletterServicesMatcher\UrlsMatcher\PerServiceUrlsMatcher\PerServiceUrlMatcherBase[]
   */
  abstract protected function getUrlPatterns(ServiceMatcherProvider $toolPattern): array;

  protected function isDomainPatternMatch(ServiceMatcherProvider $toolPattern, UriInterface $url): bool {
    $isDomainPatternMatch = FALSE;
    foreach ($toolPattern->getDomainMatchers() as $domainPattern) {
      if ($domainPattern->nowDoMatches($url)) {
        $isDomainPatternMatch = TRUE;
      }
    }
    return $isDomainPatternMatch;
  }

}

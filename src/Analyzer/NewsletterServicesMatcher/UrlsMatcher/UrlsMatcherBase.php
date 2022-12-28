<?php

declare(strict_types=1);

namespace Geeks4change\BbndAnalyzer\Analyzer\NewsletterServicesMatcher\UrlsMatcher;

use Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult\UrlList;
use Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult\UrlListMatchersResult;
use Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult\UrlListPerServiceMatches;
use Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult\UrlListPerServiceMatchesList;
use Geeks4change\BbndAnalyzer\Analyzer\NewsletterServicesMatcher\ServiceMatcherProvider;
use Geeks4change\BbndAnalyzer\Globals;
use Psr\Http\Message\UriInterface;

abstract class UrlsMatcherBase {

  public function generateUrlListResult(UrlList $urlList): UrlListMatchersResult {
    $perServiceResultList = new UrlListPerServiceMatchesList();
    $noMatchList = new UrlList();
    /** @var \Geeks4change\BbndAnalyzer\Analyzer\NewsletterServicesMatcher\ServiceMatcherProvider $toolPattern */
    foreach (Globals::get()->getServiceMatcherProviderRepository()->getServiceMatcherProviderCollection() as $toolPattern) {
      $matchedExactly = new UrlList();
      $matchedByDomain = new UrlList();
      foreach ($urlList as $url) {
        if ($this->isUrlPatternMatch($toolPattern, $url->getUrlObject())) {
          $matchedExactly->add($url);
        }
        elseif ($this->isDomainPatternMatch($toolPattern, $url->getUrlObject())) {
          $matchedByDomain->add($url);
        }
        else {
          $noMatchList->add($url);
        }
      }
      $perServiceMatches = new UrlListPerServiceMatches($toolPattern->getName(), $matchedExactly, $matchedByDomain);
      if ($perServiceMatches->isNonEmpty()) {
        $perServiceResultList->add($perServiceMatches);
      }
    }
    return new UrlListMatchersResult($perServiceResultList, $noMatchList);
}

  protected function isUrlPatternMatch(ServiceMatcherProvider $toolPattern, UriInterface $url): bool {
    $linkPatternMatch = FALSE;
    // @fixme Move to matcher method.
    foreach ($this->getUrlPatterns($toolPattern) as $urlPattern) {
      if ($urlPattern->nowDoMatches($url)) {
        $linkPatternMatch = TRUE;
      }
    }
    return $linkPatternMatch;
  }

  /**
   * @return \Geeks4change\BbndAnalyzer\Analyzer\NewsletterServicesMatcher\UrlsInfo\UrlMatcherBase[]
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

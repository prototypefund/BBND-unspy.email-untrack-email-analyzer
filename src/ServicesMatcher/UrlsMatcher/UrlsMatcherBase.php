<?php

declare(strict_types=1);

namespace Geeks4change\BbndAnalyzer\ServicesMatcher\UrlsMatcher;

use Geeks4change\BbndAnalyzer\AnalyzerResult\UrlList;
use Geeks4change\BbndAnalyzer\AnalyzerResult\UrlListMatchersResult;
use Geeks4change\BbndAnalyzer\AnalyzerResult\UrlListPerServiceMatchesList;
use Geeks4change\BbndAnalyzer\Globals;
use Geeks4change\BbndAnalyzer\ServicesMatcher\UrlsInfo\ToolPattern;
use Psr\Http\Message\UriInterface;

abstract class UrlsMatcherBase {

  public function generateUrlListResult(UrlList $urlList): UrlListMatchersResult {
    $perServiceResultList = new UrlListPerServiceMatchesList();
    $noMatchList = new UrlList();
    $hasRedirectList = new UrlList();
    $hasAnalyticsList = new UrlList();
    /** @var \Geeks4change\BbndAnalyzer\ServicesMatcher\UrlsInfo\ToolPattern $toolPattern */
    foreach (Globals::get()->getServiceInfoRepository()->getToolPatternCollection() as $toolPattern) {
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
      $perServiceResultList->add($toolPattern->getName(), $matchedExactly, $matchedByDomain);
    }
    // @todo Create $hasRedirectList.
    // @todo Create $hasAnalyticsList.
    return new UrlListMatchersResult($perServiceResultList, $noMatchList, $hasRedirectList, $hasAnalyticsList);
}

  protected function isUrlPatternMatch(ToolPattern $toolPattern, UriInterface $url): bool {
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
   * @return \Geeks4change\BbndAnalyzer\ServicesMatcher\UrlsInfo\UrlPatternBase[]
   */
  abstract protected function getUrlPatterns(ToolPattern $toolPattern): array;

  protected function isDomainPatternMatch(ToolPattern $toolPattern, UriInterface $url): bool {
    $isDomainPatternMatch = FALSE;
    foreach ($toolPattern->getDomainPatterns() as $domainPattern) {
      if ($domainPattern->nowDoMatches($url)) {
        $isDomainPatternMatch = TRUE;
      }
    }
    return $isDomainPatternMatch;
  }

}

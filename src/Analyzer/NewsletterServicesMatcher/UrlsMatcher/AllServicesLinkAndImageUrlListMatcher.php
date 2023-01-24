<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\NewsletterServicesMatcher\UrlsMatcher;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\UrlTypeEnum;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\TypedUrlList;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\TypedUrlListPerProviderBuilder;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\NewsletterServicesMatcher\ServiceMatcherProvider;
use Geeks4change\UntrackEmailAnalyzer\Globals;
use Psr\Http\Message\UriInterface;

final class AllServicesLinkAndImageUrlListMatcher {

  /**
   * @return array{
   *   exact: \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\TypedUrlListPerProvider,
   *   domain: \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\TypedUrlListPerProvider,
   * }
   */
  public function generateMatches(TypedUrlList $linkAndImageUrlList): array {
    $exactMatches = new TypedUrlListPerProviderBuilder();
    $domainMatches = new TypedUrlListPerProviderBuilder();
    /** @var \Geeks4change\UntrackEmailAnalyzer\Analyzer\NewsletterServicesMatcher\ServiceMatcherProvider $toolPattern */
    foreach (Globals::get()->getProviderRepository()->getProviderMatchers() as $toolPattern) {
      /** @var UrlTypeEnum $urlsType */
      foreach ($linkAndImageUrlList as $urlsType => $urlList) {
        foreach ($urlList as $url) {
          if ($this->isUrlPatternMatch($urlsType, $toolPattern, $url->getUrlObject())) {
            $exactMatches->add($toolPattern->getName(), $urlsType, strval($url));
          }
          elseif ($this->isDomainPatternMatch($toolPattern, $url->getUrlObject())) {
            $domainMatches->add($toolPattern->getName(), $urlsType, strval($url));
          }
        }
      }
    }
    return [
      'exact' => $exactMatches->freeze(),
      'domain' => $domainMatches->freeze(),
    ];

  }

  protected function isUrlPatternMatch(UrlTypeEnum $urlsType, ServiceMatcherProvider $toolPattern, UriInterface $url): bool {
    $linkPatternMatch = FALSE;
    // @fixme Move to matcher method.
    foreach ($this->getUrlPatterns($urlsType, $toolPattern) as $urlPattern) {
      if ($urlPattern->match($url)) {
        $linkPatternMatch = TRUE;
      }
    }
    return $linkPatternMatch;
  }

  protected function getUrlPatterns(UrlTypeEnum $urlsType, ServiceMatcherProvider $toolPattern): array {
    return match($urlsType) {
      UrlTypeEnum::Link => $toolPattern->getLinkUrlMatchers(),
      UrlTypeEnum::Image => $toolPattern->getImageUrlMatchers(),
    };
  }

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

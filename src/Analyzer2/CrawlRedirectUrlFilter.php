<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer2;

use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\UrlItemBag;
use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\UrlItemBagBuilder;
use Geeks4change\UntrackEmailAnalyzer\Matcher2\MatcherManager;

final class CrawlRedirectUrlFilter {

  public function __construct(
    protected MatcherManager $matcherManager = new MatcherManager(),
  ) {}

  public function filterUrls(UrlItemBag $urlItemBag): UrlItemBag {
    $builder = new UrlItemBagBuilder();
    foreach ($urlItemBag->urlItems as $urlItem) {
      foreach ($this->matcherManager->getMatchers() as $id => $matcher) {
        if ($matcher->matchUnsubscribeUrl($urlItem)) {
        }
      }
    }
    return $builder->freeze();
  }

}

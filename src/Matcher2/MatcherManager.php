<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Matcher2;

use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Header\HeaderItemBag;
use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Header\HeaderItemInfoBag;
use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Header\HeaderItemInfoBagBuilder;
use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Header\HeaderItemMatch;
use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\UrlItemInfoBag;
use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\UrlItemInfoBagBuilder;
use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\UrlItemMatchType\ByDomainUrlMatch;
use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\UrlItemMatchType\TechnicalUrlMatch;
use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\UrlItemMatchType\UserTrackingUrlMatch;

final class MatcherManager {

  protected array $matcherNames;

  protected array $matchers;

  public function matchHeaders(HeaderItemBag $headerItemBag): HeaderItemInfoBag {
    $builder = HeaderItemInfoBagBuilder::fromHeaderItemBag($headerItemBag);
    foreach ($this->getMatchers() as $matcherId => $matcher) {
      foreach ($headerItemBag->items as $item) {
        $match = $matcher->matchHeader($item);
        if (isset($match)) {
          $builder->addMatch($item, new HeaderItemMatch($matcherId, $match));
        }
      }
    }
    return $builder->freeze();
  }

  public function matchTechnicalUrls(UrlItemInfoBag $urlItemInfoBag): UrlItemInfoBag {
    $builder = UrlItemInfoBagBuilder::fromUrlItemInfoBag($urlItemInfoBag);
    foreach ($urlItemInfoBag->urlItemInfosByUrl as $urlItemInfo) {
      $urlItem = $urlItemInfo->urlItem;
      foreach ($this->getMatchers() as $id => $matcher) {
        if ($matcher->matchTechnicalUrl($urlItem)) {
          $builder->forUrlItem($urlItem)->addTechnicalUrlMatch(new TechnicalUrlMatch($id));
        }
      }
    }
    return $builder->freeze();
  }

  public function matchUserTrackingUrls(UrlItemInfoBag $urlItemInfoBag): UrlItemInfoBag {
    $builder = UrlItemInfoBagBuilder::fromUrlItemInfoBag($urlItemInfoBag);
    foreach ($urlItemInfoBag->urlItemInfosByUrl as $urlItemInfo) {
      $urlItem = $urlItemInfo->urlItem;
      foreach ($this->getMatchers() as $id => $matcher) {
        if ($matcher->matchUserTrackingUrl($urlItem)) {
          $builder->forUrlItem($urlItem)->addUserTrackingUrlMatch(new UserTrackingUrlMatch($id));
        }
        elseif ($matcher->matchDomainUrl($urlItem)) {
          $builder->forUrlItem($urlItem)->addByDomainUrlMatch(new ByDomainUrlMatch($id));
        }
      }
    }
    return $builder->freeze();
  }

  /**
   * @return array<string, \Geeks4change\UntrackEmailAnalyzer\Matcher2\MatcherInterface>
   */
  public function getMatchers(): array {
    return $this->matchers ?? ($this->matchers = $this->discoverMatchers());
  }

  /**
   * @return array<string, \Geeks4change\UntrackEmailAnalyzer\Matcher2\MatcherInterface>
   */
  protected function discoverMatchers(): array {
    $matcherContainerDir = __DIR__;
    $matcherContainerNamespace = __NAMESPACE__;
    $matchers = [];
    foreach (glob("$matcherContainerDir/*", GLOB_ONLYDIR) as $matcherDir) {
      $name = basename($matcherDir);
      $matcher = new ("$matcherContainerNamespace\\{$name}\\{$name}Matcher")();
      if ($matcher instanceof MatcherInterface) {
        $id = $matcher->getId();
        $matchers[$id] = $matcher;
      }
      else {
        throw new \LogicException("Invalid matcher dir: $matcherDir");
      }
    }
    return $matchers;
  }

}

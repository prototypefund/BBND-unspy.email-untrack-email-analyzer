<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Matcher2;

use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Header\HeaderItemBag;
use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Header\HeaderItemInfoBag;
use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Header\HeaderItemInfoBagBuilder;
use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Header\HeaderItemMatch;
use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\UrlItemInfoBag;
use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\UrlItemInfoBagBuilder;
use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\UrlItemMatchType\UrlItemMatchType;

final class MatcherManager {

  protected array $matcherNames;

  protected array $matchers;

  public function matchHeaders(HeaderItemBag $headerItemBag): HeaderItemInfoBag {
    $builder = HeaderItemInfoBagBuilder::fromHeaderItemBag($headerItemBag);
    foreach ($this->getMatchers() as $id => $matcher) {
      foreach ($headerItemBag->items as $item) {
        if ($matcher->matchHeader($item)) {
          $builder->addMatch($item, new HeaderItemMatch($id));
        }
      }
    }
    return $builder->freeze();
  }

  public function matchUnsubscribeUrls(UrlItemInfoBag $urlItemInfoBag): UrlItemInfoBag {
    $builder = UrlItemInfoBagBuilder::fromUrlItemInfoBag($urlItemInfoBag);
    foreach ($urlItemInfoBag->urlItemInfos as $urlItemInfo) {
      $urlItem = $urlItemInfo->urlItem;
      foreach ($this->getMatchers() as $id => $matcher) {
        if ($matcher->matchUnsubscribeUrl($urlItem)) {
          $builder->addCreateMatch($urlItem, $id, UrlItemMatchType::Unsubscribe());
        }
      }
    }
    return $builder->freeze();
  }

  public function matchUrls(UrlItemInfoBag $urlItemInfoBag): UrlItemInfoBag {
    $builder = UrlItemInfoBagBuilder::fromUrlItemInfoBag($urlItemInfoBag);
    foreach ($urlItemInfoBag->urlItemInfos as $urlItemInfo) {
      $urlItem = $urlItemInfo->urlItem;
      foreach ($this->getMatchers() as $id => $matcher) {
        if ($matcher->matchUserTrackingUrl($urlItem)) {
          $builder->addCreateMatch($urlItem, $id, UrlItemMatchType::UserTracking());
        }
        elseif ($matcher->matchDomainUrl($urlItem)) {
          $builder->addCreateMatch($urlItem, $id, UrlItemMatchType::UserTracking());
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

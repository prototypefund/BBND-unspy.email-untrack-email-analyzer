<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Matcher;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\Result\Header\HeaderItemBag;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\Result\Header\HeaderItemInfoBag;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\Result\Header\HeaderItemInfoBagBuilder;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\Result\Header\HeaderItemMatch;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\Result\Url\UrlItemInfoBag;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\Result\Url\UrlItemInfoBagBuilder;
use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\UrlItemMatch\ByDomainMatch;
use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\UrlItemMatch\TechnicalUrlMatch;
use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\UrlItemMatch\UserTrackingUrlMatch;

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

  public function matchUrls(UrlItemInfoBag $urlItemInfoBag): UrlItemInfoBag {
    $builder = UrlItemInfoBagBuilder::fromUrlItemInfoBag($urlItemInfoBag);
    foreach ($urlItemInfoBag->urlItemInfos as $urlItemInfo) {
      $urlItem = $urlItemInfo->urlItem;
      foreach ($this->getMatchers() as $id => $matcher) {
        if ($match = $matcher->matchUrl($urlItem)) {
          $builder->forUrlItem($urlItem)->addMatch($match);
        }
      }
    }
    return $builder->freeze();
  }

  /**
   * @return array<string, \Geeks4change\UntrackEmailAnalyzer\Matcher\MatcherInterface>
   */
  public function getMatchers(): array {
    return $this->matchers ?? ($this->matchers = $this->discoverMatchers());
  }

  /**
   * @return array<string, \Geeks4change\UntrackEmailAnalyzer\Matcher\MatcherInterface>
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

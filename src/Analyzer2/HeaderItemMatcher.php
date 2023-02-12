<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer2;

use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Header\HeaderItemBag;
use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Header\HeaderItemInfoBag;
use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Header\HeaderItemInfoBagBuilder;
use Geeks4change\UntrackEmailAnalyzer\Matcher2\MatcherManager;

final class HeaderItemMatcher {

  public function __construct(
    protected MatcherManager $matcherManager = new MatcherManager(),
  ) {}

  public function matchHeaders(HeaderItemBag $items): HeaderItemInfoBag {
    $builder = HeaderItemInfoBagBuilder::fromHeaderItemBag($items);
    foreach ($this->matcherManager->getMatchers() as $id => $matcher) {
      $matcher->matchHeaders($builder);
    }
    return $builder->freeze();
  }

}

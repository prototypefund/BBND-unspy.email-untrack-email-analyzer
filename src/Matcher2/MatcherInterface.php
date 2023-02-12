<?php

namespace Geeks4change\UntrackEmailAnalyzer\Matcher2;

use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Header\HeaderItemInfoBagBuilder;
use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\UrlItem;

interface MatcherInterface {

  public function getId(): string;

  public function matchHeaders(HeaderItemInfoBagBuilder $builder): void;

  public function matchUnsubscribeUrl(UrlItem $urlItem): bool;

}

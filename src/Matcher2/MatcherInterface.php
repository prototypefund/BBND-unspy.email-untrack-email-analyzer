<?php

namespace Geeks4change\UntrackEmailAnalyzer\Matcher2;

use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Header\HeaderItem;
use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\Match\ProviderMatch;
use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\UrlItem;

interface MatcherInterface {

  public function getId(): string;

  public function matchHeader(HeaderItem $item): ?bool;

  public function matchUrl(UrlItem $urlItem): ?ProviderMatch;

}

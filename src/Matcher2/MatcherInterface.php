<?php

namespace Geeks4change\UntrackEmailAnalyzer\Matcher2;

use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Result\Header\HeaderItem;
use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Result\Url\Match\ProviderMatch;
use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Result\Url\UrlItem;

interface MatcherInterface {

  public function getId(): string;

  public function matchHeader(HeaderItem $item): ?bool;

  public function matchUrl(UrlItem $urlItem): ?ProviderMatch;

}

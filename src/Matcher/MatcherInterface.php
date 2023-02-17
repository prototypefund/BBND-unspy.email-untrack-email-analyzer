<?php

namespace Geeks4change\UntrackEmailAnalyzer\Matcher;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\Result\Header\HeaderItem;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\Result\Header\HeaderItemMatch;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\Result\Url\Match\ProviderMatch;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\Result\Url\UrlItem;

interface MatcherInterface {

  public function matchHeader(HeaderItem $item): ?HeaderItemMatch;

  public function matchUrl(UrlItem $urlItem): ?ProviderMatch;

}

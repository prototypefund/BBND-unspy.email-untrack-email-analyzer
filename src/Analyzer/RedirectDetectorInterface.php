<?php

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\TypedUrlRedirectChainList;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\TypedUrlList;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\UrlList;

interface RedirectDetectorInterface {

  public function detectRedirect(TypedUrlList $linkAndImageUrlList, UrlList $urlsToExclude): TypedUrlRedirectChainList;

}
<?php

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\LinkAndImageRedirectInfoList;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\LinkAndImageUrlList;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\UrlList;

interface RedirectDetectorInterface {

  public function detectRedirect(LinkAndImageUrlList $linkAndImageUrlList, UrlList $urlsToExclude): LinkAndImageRedirectInfoList;

}
<?php

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\LinkAndImageRedirectInfoList;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\LinkAndImageUrlList;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\UrlList;

interface RedirectDetectorInterface {

  public function detectRedirect(LinkAndImageUrlList $linkAndImageUrlList, UrlList $urlsToExclude): LinkAndImageRedirectInfoList;

}
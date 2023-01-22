<?php

namespace Geeks4change\UntrackEmailAnalyzer\RedirectResolver;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\UrlList;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\UrlRedirectInfoList;

interface RedirectResolverInterface {

  public function resolveRedirects(UrlList $urlList): UrlRedirectInfoList;

}
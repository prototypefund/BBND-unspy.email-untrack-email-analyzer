<?php

namespace Geeks4change\UntrackEmailAnalyzer\RedirectResolver;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\UrlList;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\UrlRedirectInfoList;

interface RedirectResolverInterface {

  /**
   * Resolve redirections.
   *
   * @fixme Clean this up:
   *   - Move everything to caller.
   *   - Throttle async so it won't hit rate limits.
   *   - Use $excludeUrlList
   */
  public function resolveRedirects(UrlList $urlList): UrlRedirectInfoList;

}
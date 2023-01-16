<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\RedirectResolver;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\UrlList;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\UrlRedirectInfoList;

final class NullRedirectResolver implements RedirectResolverInterface {

  public function resolveRedirects(UrlList $urlList): UrlRedirectInfoList {
    return new UrlRedirectInfoList();
  }

}

<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\RedirectResolver;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\UrlList;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\UrlRedirectInfoList;

final class NullRedirectResolver implements RedirectResolverInterface {

  public function resolveRedirects(UrlList $urlList): UrlRedirectInfoList {
    return new UrlRedirectInfoList();
  }

}

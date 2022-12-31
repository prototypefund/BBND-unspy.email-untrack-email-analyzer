<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\NewsletterServicesMatcher\UrlsMatcher;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\NewsletterServicesMatcher\ServiceMatcherProvider;

final class AllServicesImageUrlsMatcher extends AllServicesUrlsMatcherBase {

  protected function getUrlPatterns(ServiceMatcherProvider $toolPattern): array {
    return $toolPattern->getImageUrlMatchers();
  }

}

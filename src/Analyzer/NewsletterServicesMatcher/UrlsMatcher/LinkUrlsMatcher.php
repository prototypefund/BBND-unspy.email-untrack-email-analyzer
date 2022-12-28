<?php

declare(strict_types=1);

namespace Geeks4change\BbndAnalyzer\Analyzer\NewsletterServicesMatcher\UrlsMatcher;

use Geeks4change\BbndAnalyzer\Analyzer\NewsletterServicesMatcher\ServiceMatcherProvider;

final class LinkUrlsMatcher extends UrlsMatcherBase{

  protected function getUrlPatterns(ServiceMatcherProvider $toolPattern): array {
    return $toolPattern->getLinkUrlMatchers();
  }

}

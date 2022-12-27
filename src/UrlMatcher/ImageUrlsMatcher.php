<?php

declare(strict_types=1);

namespace Geeks4change\BbndAnalyzer\UrlMatcher;

use Geeks4change\BbndAnalyzer\Pattern\ToolPattern;

final class ImageUrlsMatcher extends UrlsMatcherBase {

  protected function getUrlPatterns(ToolPattern $toolPattern): array {
    return $toolPattern->getImagePatterns();
  }

}

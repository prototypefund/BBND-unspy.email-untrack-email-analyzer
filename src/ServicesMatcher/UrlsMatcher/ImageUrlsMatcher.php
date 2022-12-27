<?php

declare(strict_types=1);

namespace Geeks4change\BbndAnalyzer\ServicesMatcher\UrlsMatcher;

use Geeks4change\BbndAnalyzer\ServicesMatcher\ToolPattern;

final class ImageUrlsMatcher extends UrlsMatcherBase {

  protected function getUrlPatterns(ToolPattern $toolPattern): array {
    return $toolPattern->getImagePatterns();
  }

}

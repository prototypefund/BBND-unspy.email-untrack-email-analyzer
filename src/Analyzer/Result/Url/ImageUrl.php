<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\Result\Url;

final class ImageUrl extends UrlItem {

  protected function getType(): string {
    return 'image';
  }

}

<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data;

use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\UrlItem;

final class LinkUrl extends UrlItem {

  public function __construct(
                    string $url,
    public readonly string $text,
  ) {
    parent::__construct($url);
  }

}

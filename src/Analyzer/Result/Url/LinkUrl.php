<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\Result\Url;

final class LinkUrl extends UrlItem {

  public function __construct(
                    string $url,
    public readonly string $text,
  ) {
    parent::__construct($url);
  }

}

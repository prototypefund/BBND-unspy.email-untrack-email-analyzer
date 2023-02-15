<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url;

use Geeks4change\UntrackEmailAnalyzer\Anon;

abstract class UrlItem {

  public function __construct(
    public readonly string      $url,
  ) {}

  public function anonymize(): self {
    return new static(
      Anon::url($this->url),
    );
  }

}

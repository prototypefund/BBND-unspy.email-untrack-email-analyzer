<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\Result\Url;

use Geeks4change\UntrackEmailAnalyzer\Utility\Anon;

abstract class UrlItem {

  public function __construct(
    public readonly string      $url,
  ) {}

  abstract public function getType(): string;

  public function anonymize(): self {
    return new static(
      Anon::url($this->url),
    );
  }

}

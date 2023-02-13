<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url;

use Geeks4change\UntrackEmailAnalyzer\Anon;

final class UrlItem {

  public function __construct(
    public readonly UrlItemType $type,
    public readonly string      $url,
  ) {}

  public function anonymize(): self {
    return new self(
      $this->type,
      Anon::url($this->url),
    );
  }

}

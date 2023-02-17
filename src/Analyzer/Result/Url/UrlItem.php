<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\Result\Url;

use Geeks4change\UntrackEmailAnalyzer\Utility\Anon;

abstract class UrlItem {

  public readonly string $type;

  public function __construct(
    public readonly string      $url,
  ) {
    // Provide the type to the array converter, too.
    $this->type = $this->getType();
  }

  abstract protected function getType(): string;

  public function anonymize(): self {
    return new static(
      Anon::url($this->url),
    );
  }

}

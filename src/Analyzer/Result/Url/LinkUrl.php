<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\Result\Url;

use Geeks4change\UntrackEmailAnalyzer\Utility\Anon;

final class LinkUrl extends UrlItem {

  public function __construct(
                    string $url,
    public readonly string $text,
  ) {
    parent::__construct($url);
  }

  public function anonymize(): self {
    return new static(
      Anon::url($this->url),
      Anon::text($this->text),
    );
  }

}

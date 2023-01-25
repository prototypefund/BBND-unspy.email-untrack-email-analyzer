<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails;

final class UrlRedirectInfo implements \Stringable {

  public readonly array $redirectUrls;

  public function __construct(public readonly string $url, string ...$redirectUrls) {
    $this->redirectUrls = $redirectUrls;
  }

  public function __toString(): string {
    return implode(' => ', [$this->url, ...$this->redirectUrls]);
  }

}

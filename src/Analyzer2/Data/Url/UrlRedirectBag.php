<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url;

final class UrlRedirectBag {

  /**
   * Redirects by URL.
   *
   * @param array<string, \Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\UrlRedirect> $urlRedirects
   */
  public function __construct(
    public readonly array $urlRedirects,
  ) {}

}

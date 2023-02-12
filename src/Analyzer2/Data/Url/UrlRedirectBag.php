<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url;

final class UrlRedirectBag {

  /**
   * @var list<\Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\UrlRedirect> $urlRedirects
   */
  public readonly array $urlRedirects;

  /**
   * Redirects by URL.
   *
   * @param list<\Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\UrlRedirect> $urlRedirects
   */
  public function __construct(array $urlRedirects) {
    $urlRedirectsByUrl = [];
    foreach ($urlRedirects as $urlRedirect) {
      $urlRedirectsByUrl[$urlRedirect->url] = $urlRedirectsByUrl;
    }
    $this->urlRedirects = $urlRedirectsByUrl;
  }

}

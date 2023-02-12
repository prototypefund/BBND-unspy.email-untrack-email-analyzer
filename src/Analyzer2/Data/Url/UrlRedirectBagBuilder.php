<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url;

final class UrlRedirectBagBuilder {

  /**
   * Redirects by URL.
   *
   * @var array<string, \Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\UrlRedirect> $urlRedirects
   */
  protected array $urlRedirects;

  public function __construct() {}

  public function addUrlRedirect(UrlItemType $type, string $url, ?string $redirect): void {
    $this->urlRedirects[$url] = new UrlRedirect($type, $url, $redirect);
  }

  public function freeze(): UrlRedirectBag {
    return new UrlRedirectBag($this->urlRedirects);
  }

}

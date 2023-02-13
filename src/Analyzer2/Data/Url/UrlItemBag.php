<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url;

final class UrlItemBag {

  /**
   * @param list<\Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\UrlItem> $urlItemsByUrl
   */
  public function __construct(
    // @todo Ensure 'by url'.
    public readonly array $urlItemsByUrl,
  ) {}

}

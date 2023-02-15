<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer2\Result\Url;

final class UrlItemBag {

  /**
   * @param list<\Geeks4change\UntrackEmailAnalyzer\Analyzer2\Result\Url\UrlItem> $urlItemsByUrl
   */
  public function __construct(
    // @todo Ensure 'by url'.
    public readonly array $urlItemsByUrl,
  ) {}

}

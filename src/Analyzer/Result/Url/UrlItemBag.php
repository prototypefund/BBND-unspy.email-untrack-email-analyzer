<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\Result\Url;

final class UrlItemBag {

  /**
   * @param list<\Geeks4change\UntrackEmailAnalyzer\Analyzer\Result\Url\UrlItem> $urlItemsByUrl
   */
  public function __construct(
    // @todo Ensure 'by url'.
    public readonly array $urlItemsByUrl,
  ) {}

}

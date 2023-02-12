<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url;

final class UrlItemInfo {

  /**
   * @param list<\Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\UrlItemMatch> $matches
   */
  public function __construct(
    public readonly UrlRedirect $urlRedirect,
    public readonly array   $matches,
  ) {}

}

<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url;

final class UrlItemInfoBag {

  /**
   * @param array<string, \Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\UrlItemInfo> $urlItemInfos
   */
  public function __construct(
    public readonly array $urlItemInfos,
  ) {}

}

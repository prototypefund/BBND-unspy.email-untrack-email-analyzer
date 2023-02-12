<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Header;

final class HeaderItemInfoBag {

  /**
   * @param list<\Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Header\HeaderItemInfo> $infos
   */
  public function __construct(
    public readonly array $infos,
  ) {}

}

<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Header;

final class HeaderItemBag {

  /**
   * @param list<\Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Header\HeaderItem> $items
   */
  public function __construct(
    public readonly array $items,
  ) {}

}

<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Header;

final class HeaderItemInfo {

  /**
   * @param list<\Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Header\HeaderItemMatch> $matches
   */
  public function __construct(
    public readonly HeaderItem $headerItem,
    public readonly array $matches,
  ) {}

}

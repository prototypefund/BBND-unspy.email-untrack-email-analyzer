<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer2\Result\Header;

final class HeaderItemInfoBuilder {

  protected array $matches = [];

  public function __construct(
    protected HeaderItem $headerItem,
  ) {}

  public function addMatch(HeaderItemMatch $match): void {
    $this->matches[] = $match;
  }

  public function freeze(): HeaderItemInfo {
    return new HeaderItemInfo($this->headerItem, $this->matches);
  }

}

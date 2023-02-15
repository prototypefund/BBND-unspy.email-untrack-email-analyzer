<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer2\Result\Header;

final class HeaderItemBagBuilder {

  /**
   * @var HeaderItem $items
   */
  protected array $items = [];

  public function __construct() {}

  public function addItem(HeaderItem $item): void {
    $this->items[] = $item;
  }

  public function freeze(): HeaderItemBag {
    return new HeaderItemBag($this->items);
  }

}

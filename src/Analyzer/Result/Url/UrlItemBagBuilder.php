<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\Result\Url;

final class UrlItemBagBuilder {

  protected array $urls = [];

  public function __construct() {}

  public function addUrlItem(UrlItem $urlItem) {
    $this->urls[$urlItem->url] = $urlItem;
  }

  public function freeze(): UrlItemBag {
    return new UrlItemBag(array_values($this->urls));
  }

}

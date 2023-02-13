<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url;

final class UrlItemBagBuilder {

  protected array $urls = [];

  public function __construct() {}

  public function addUrl(UrlItemType $urlType, string $url) {
    $this->urls[$url] = new UrlItem($urlType, $url);
  }

  public function freeze(): UrlItemBag {
    return new UrlItemBag(array_values($this->urls));
  }

}

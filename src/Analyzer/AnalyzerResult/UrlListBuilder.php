<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult;

final class UrlListBuilder {

  protected array $urls = [];

  public function add(string $url) {
    $this->urls[$url] = new UrlItem($url);
  }

  public function freeze() {
    return new UrlList($this->urls);
  }

}

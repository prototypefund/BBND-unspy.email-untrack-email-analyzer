<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\UrlExtractor;

final class PixelsUrlExtractor extends UrlExtractorBase {

  protected function extractUrls(): \Traversable {
    return $this->crawler->filterXPath('//img[@width="1" and @height="1"]/@src');
  }

}

<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\UrlExtractor;

final class ImagesUrlExtractor extends UrlExtractorBase {

  protected function extractUrls(): \Traversable {
    return $this->crawler->filterXPath('//img/@src');
  }

}

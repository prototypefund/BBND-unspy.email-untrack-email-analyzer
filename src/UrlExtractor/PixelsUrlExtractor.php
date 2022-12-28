<?php

declare(strict_types=1);

namespace Geeks4change\BbndAnalyzer\UrlExtractor;

use Geeks4change\BbndAnalyzer\Utility\ThrowMethodTrait;

final class PixelsUrlExtractor extends UrlExtractorBase {

  use ThrowMethodTrait;

  protected function extractDomNodeList(\DOMXPath $xpath): \DomNodeList {
    return $xpath->query("//img[@src and @width='1' and @height='1']") ?? self::throwUnexpectedValue();
  }

  protected function extractUrlSpec(\DomNode $domNode): string {
    return $domNode->attributes->getNamedItem('src')->value;
  }

}

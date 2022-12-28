<?php

declare(strict_types=1);

namespace Geeks4change\BbndAnalyzer\UrlExtractor;

use Geeks4change\BbndAnalyzer\Analysis\Summary\LinkUrlList;
use Geeks4change\BbndAnalyzer\Utility\ThrowMethodTrait;

final class LinksUrlExtractor extends UrlExtractorBase {

  use ThrowMethodTrait;

  protected function extractDomNodeList(\DOMXPath $xpath): \DomNodeList {
    return $xpath->query('//a[@href]') ?? self::throwUnexpectedValue();
  }


  protected function extractUrlSpec(\DomNode $domNode): string {
    return $domNode->attributes->getNamedItem('href')->value;
  }

}

<?php

declare(strict_types=1);

namespace Geeks4change\BbndAnalyzer\Html;

use Geeks4change\BbndAnalyzer\Analysis\Summary\LinkUrlList;
use Geeks4change\BbndAnalyzer\Analysis\Summary\UrlList;
use Geeks4change\BbndAnalyzer\Utility\ThrowMethodTrait;

final class LinkExtractor extends HtmlExtractorBase {

  use ThrowMethodTrait;

  protected function extractDomNodeList(\DOMXPath $xpath): \DomNodeList {
    return $xpath->query('//a[@href]') ?? self::throwUnexpectedValue();
  }


  protected function extractUrlSpec(\DomNode $domNode): string {
    return $domNode->attributes->getNamedItem('href')->value;
  }

}

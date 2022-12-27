<?php

namespace Geeks4change\BbndAnalyzer\DomElement;

use Geeks4change\BbndAnalyzer\DomainAliases\DomainAliasesResolver;

final class Image implements DomElementInterface {

  protected DomUrl $url;

  protected bool $isPixel;

  /**
   * @param \Geeks4change\BbndAnalyzer\DomElement\DomUrl $url
   * @param bool $isPixel
   */
  private function __construct(DomUrl $url, bool $isPixel) {
    $this->url = $url;
    $this->isPixel = $isPixel;
  }


  public static function fromDomNode(\DOMNode $domNode): ?self {
    $isPixel =
      ($attributes = $domNode->attributes)
      && ($heightAttr = $attributes->getNamedItem('height'))
      && ($widthAttr = $attributes->getNamedItem('width'))
      && ($heightAttr->value == 1)
      && ($widthAttr->value == 1)
    ;
    $src = $domNode->attributes->getNamedItem('src')->value;
    $url = DomUrl::create($src);
    return $url ? new self($url, $domNode->textContent) : NULL;
  }

  /**
   * @return \Geeks4change\BbndAnalyzer\DomElement\DomUrl
   */
  public function getUrl(): DomUrl {
    return $this->url;
  }

  /**
   * @return bool
   */
  public function isPixel(): bool {
    return $this->isPixel;
  }

}
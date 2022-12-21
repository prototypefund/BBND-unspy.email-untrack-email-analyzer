<?php

namespace Geeks4change\BbndAnalyzer\DomElement;

final class Image implements DomElementInterface {

  protected Url $url;

  protected bool $isPixel;

  /**
   * @param \Geeks4change\BbndAnalyzer\DomElement\Url $url
   * @param bool $isPixel
   */
  private function __construct(Url $url, bool $isPixel) {
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
    return new self(new Url($src), $isPixel);
  }

  /**
   * @return \Geeks4change\BbndAnalyzer\DomElement\Url
   */
  public function getUrl(): Url {
    return $this->url;
  }

  /**
   * @return bool
   */
  public function isPixel(): bool {
    return $this->isPixel;
  }

}
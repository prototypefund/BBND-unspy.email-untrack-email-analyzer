<?php

namespace Geeks4change\BbndAnalyzer\DomElement;

final class Link implements DomElementInterface  {

  protected Url $url;

  protected string $text;

  private function __construct(Url $url, string $text) {
    $this->url = $url;
    $this->text = $text;
  }


  public static function fromDomNode(\DOMNode $domNode): ?self {
    $href = $domNode->attributes->getNamedItem('href')->value;
    $scheme = parse_url($href)['scheme'] ?? NULL;
    return ($scheme === 'http' || $scheme === 'https') ?
      new self(new Url($href), $domNode->textContent) : NULL;
  }

  /**
   * @return \Geeks4change\BbndAnalyzer\DomElement\Url
   */
  public function getUrl(): Url {
    return $this->url;
  }

  /**
   * @return string
   */
  public function getText(): string {
    return $this->text;
  }

}
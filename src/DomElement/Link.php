<?php

namespace Geeks4change\BbndAnalyzer\DomElement;

use Geeks4change\BbndAnalyzer\DomainNames\DomainNameResolver;

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
    $url = Url::create($href);
    return $url ? new self($url, $domNode->textContent) : NULL;
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
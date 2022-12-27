<?php

namespace Geeks4change\BbndAnalyzer\DomElement;

use Geeks4change\BbndAnalyzer\DomainAliases\DomainAliasesResolver;

final class Link implements DomElementInterface  {

  protected DomUrl $url;

  protected string $text;

  public function __construct(DomUrl $url, string $text = '') {
    $this->url = $url;
    $this->text = $text;
  }


  public static function fromDomNode(\DOMNode $domNode): ?self {
    $href = $domNode->attributes->getNamedItem('href')->value;
    $scheme = parse_url($href)['scheme'] ?? NULL;
    $url = DomUrl::create($href);
    return $url ? new self($url, $domNode->textContent) : NULL;
  }

  /**
   * @return \Geeks4change\BbndAnalyzer\DomElement\DomUrl
   */
  public function getUrl(): DomUrl {
    return $this->url;
  }

  /**
   * @return string
   */
  public function getText(): string {
    return $this->text;
  }

}
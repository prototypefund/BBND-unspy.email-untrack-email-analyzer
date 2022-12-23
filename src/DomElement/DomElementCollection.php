<?php

namespace Geeks4change\BbndAnalyzer\DomElement;

use Geeks4change\BbndAnalyzer\DomainNames\DomainNameResolver;
use Geeks4change\BbndAnalyzer\Utility\ArrayAccessTrait;
use Geeks4change\BbndAnalyzer\Utility\ThrowMethodTrait;
use Masterminds\HTML5;

final class DomElementCollection implements \IteratorAggregate, \ArrayAccess, \Countable {

  use ThrowMethodTrait;
  use ArrayAccessTrait;

  /**
   * @var array<\Geeks4change\BbndAnalyzer\DomElement\DomElementInterface>
   */
  protected array $domElements;

  /**
   * @param \Geeks4change\BbndAnalyzer\DomElement\DomElementInterface[] $domElements
   */
  private function __construct(array $domElements) {
    $this->domElements = $domElements;
  }

  /**
   * The only way to construct a DomElementCollection.
   */
  protected static function builder(): DomElementCollectionBuilder {
    return new DomElementCollectionBuilder(\Closure::fromCallable(fn(array $domElements) => new self($domElements)));
  }

  public static function fromHtml(string $html, DomainNameResolver $domainNameResolver): DomElementCollection {
    // Extract HTML part.
    $dom = (new HTML5(['disable_html_ns' => TRUE]))->loadHTML($html);
    $xpath = new \DOMXPath($dom);

    // @todo Move to DomElementCollection::fromHtml();
    $domElementCollectionBuilder = DomElementCollection::builder();
    $linksList = $xpath->query('//a[@href]') ?? self::throwUnexpectedValue();
    $imagesList = $xpath->query('//img[@src]') ?? self::throwUnexpectedValue();
    foreach (iterator_to_array($linksList) as $linkNode) {
      $maybeLink = Link::fromDomNode($linkNode);
      if ($maybeLink) {
        $domElementCollectionBuilder->add($maybeLink);
      }
    }
    foreach (iterator_to_array($imagesList) as $imageNode) {
      $maybeImage = Image::fromDomNode($imageNode);
      if ($maybeImage) {
        $domElementCollectionBuilder->add($maybeImage);
      }
    }
    $domElementCollection = $domElementCollectionBuilder->freeze();
    return $domElementCollection;
  }

  protected function &getInnerArray(): array {
    return $this->domElements;
  }

  /**
   * @return array<\Geeks4change\BbndAnalyzer\DomElement\DomElementInterface>
   */
  public function getDomElements(): array {
    return $this->domElements;
  }

}

<?php

namespace Geeks4change\BbndAnalyzer\DomElement;

class DomElementCollectionBuilder {

  /**
   * @var array<\Geeks4change\BbndAnalyzer\DomElement\DomElementInterface>
   */
  protected array $domElements = [];

  protected \Closure $constructor;

  /**
   * Nothing to see here. Use PatternCollection::builder.
   */
  public function __construct(\Closure $constructor) {
    $this->constructor = $constructor;
  }

  public function add(DomElementInterface $domElement) {
    $this->domElements[] = $domElement;
  }

  public function freeze(): DomElementCollection {
    return ($this->constructor)($this->domElements);
  }


}

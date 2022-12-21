<?php

namespace Geeks4change\BbndAnalyzer\Matching;

use Geeks4change\BbndAnalyzer\DomElement\DomElementInterface;

abstract class MatchBase {

  protected DomElementInterface $domElement;

  public function __construct(DomElementInterface $domElement) {
    $this->domElement = $domElement;
  }

  public function getDomElement(): DomElementInterface {
    return $this->domElement;
  }

}

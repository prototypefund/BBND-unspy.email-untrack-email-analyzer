<?php

namespace Geeks4change\BbndAnalyzer\Matching;

use Geeks4change\BbndAnalyzer\DomElement\DomElementInterface;

class MatchBase {

  protected DomElementInterface $domElement;

  public function getDomElement(): DomElementInterface {
    return $this->domElement;
  }

}

<?php

namespace Geeks4change\BbndAnalyzer\Matching;

use Geeks4change\BbndAnalyzer\DomElement\DomElementInterface;
use Geeks4change\BbndAnalyzer\Pattern\DomainPattern;

final class MatchByDomain extends MatchBase {

  protected DomainPattern $domainPattern;

  public function __construct(DomElementInterface $domElement, DomainPattern $domainPattern) {
    $this->domElement = $domElement;
    $this->domainPattern = $domainPattern;
  }

  public function getDomainPattern(): DomainPattern {
    return $this->domainPattern;
  }

}

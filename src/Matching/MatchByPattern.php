<?php

namespace Geeks4change\BbndAnalyzer\Matching;

use Geeks4change\BbndAnalyzer\DomElement\DomElementInterface;
use Geeks4change\BbndAnalyzer\Pattern\UrlPatternBase;

final class MatchByPattern extends MatchBase {

  protected UrlPatternBase $urlPattern;

  public function __construct(DomElementInterface $domElement, UrlPatternBase $urlPattern) {
    $this->domElement = $domElement;
    $this->urlPattern = $urlPattern;
  }

  public function getUrlPattern(): UrlPatternBase {
    return $this->urlPattern;
  }


}

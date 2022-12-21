<?php

namespace Geeks4change\BbndAnalyzer\Pattern;

use Geeks4change\BbndAnalyzer\DomElement\Link;

class UrlPatternForLink extends UrlPatternBase {

  public function matches(Link $link): bool {
    return $this->doMatches($link);
  }

}
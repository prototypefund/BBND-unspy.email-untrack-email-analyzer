<?php

namespace Geeks4change\BbndAnalyzer\ServicesMatcher\UrlsInfo;

use Geeks4change\BbndAnalyzer\DomElement\Image;

class UrlPatternForImage extends UrlPatternBase {

  public function matches(Image $image): bool {
    return $this->doMatches($image);
  }

}
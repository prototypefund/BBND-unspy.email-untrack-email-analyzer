<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer2\ResultSummary;

use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\UrlItemType;

final class TypedUrlCountBuilder {

  protected int $typeLink = 0;
  protected int $typeImage = 0;

  public function add(UrlItemType $urlType): void {
    if ($urlType === UrlItemType::Link) {
      $this->typeLink ++;
    }
    elseif ($urlType === UrlItemType::Image) {
      $this->typeImage ++;
    }
  }

  public function freeze(): TypedUrlCount {
    return new TypedUrlCount($this->typeLink, $this->typeImage);
  }

}

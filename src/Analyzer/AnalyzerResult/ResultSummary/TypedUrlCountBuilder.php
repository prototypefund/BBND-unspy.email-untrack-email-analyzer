<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultSummary;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\UrlTypeEnum;

final class TypedUrlCountBuilder {

  protected int $typeLink = 0;
  protected int $typeImage = 0;

  public function add(UrlTypeEnum $urlType, int $count): void {
    if ($urlType == UrlTypeEnum::Link) {
      $this->typeLink += $count;
    }
    elseif ($urlType == UrlTypeEnum::Image) {
      $this->typeImage += $count;
    }
  }

  public function freeze(): TypedUrlCount {
    return new TypedUrlCount($this->typeLink, $this->typeImage);
  }

}

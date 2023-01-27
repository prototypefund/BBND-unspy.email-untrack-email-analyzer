<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultSummary;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\UrlTypeEnum;

final class TypedRedirectCountBuilder {

  protected int $typeLink = 0;
  protected int $typeImage = 0;

  public function add(UrlTypeEnum $urlType) {
    if ($urlType === UrlTypeEnum::Link) {
      $this->typeLink++;
    }
    elseif ($urlType === UrlTypeEnum::Image) {
      $this->typeImage++;
    }
  }

  public function freeze(): TypedUrlRedirectCount {
    return new TypedUrlRedirectCount($this->typeLink, $this->typeImage);
  }


}

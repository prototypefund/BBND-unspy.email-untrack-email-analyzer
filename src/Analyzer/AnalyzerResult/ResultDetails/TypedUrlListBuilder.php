<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails;

final class TypedUrlListBuilder {

  protected UrlListBuilder $typeLink;
  protected UrlListBuilder $typeImage;

  public function __construct() {
    $this->typeLink = new UrlListBuilder();
    $this->typeImage = new UrlListBuilder();
  }

  public function add(UrlTypeEnum $type, string $url) {
    $builder = match ($type) {
      UrlTypeEnum::Link => $this->typeLink,
      UrlTypeEnum::Image => $this->typeImage,
    };
    $builder->add($url);
  }

  public function freeze(): TypedUrlList {
    return new TypedUrlList($this->typeLink->freeze(), $this->typeImage->freeze());
  }

}

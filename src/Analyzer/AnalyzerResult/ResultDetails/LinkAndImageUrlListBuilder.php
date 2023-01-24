<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails;

final class LinkAndImageUrlListBuilder {
  protected UrlListBuilder $links;
  protected UrlListBuilder $images;

  public function __construct() {
    $this->links = new UrlListBuilder();
    $this->images = new UrlListBuilder();
  }

  public function add(LinkAndImageEnum $type, string $url) {
    $builder = match ($type) {
      LinkAndImageEnum::Link => $this->links,
      LinkAndImageEnum::Image => $this->images,
    };
    $builder->add($url);
  }

  public function freeze(): LinkAndImageUrlList {
    return new LinkAndImageUrlList($this->links->freeze(), $this->images->freeze());
  }

}

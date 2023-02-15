<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\Result\Url;

final class UrlItemInfoBag {

  /**
   * @var UrlItemInfo $urlItemInfos
   */
  public readonly array $urlItemInfos;

  /**
   * @param UrlItemInfo $urlItemInfos
   */
  public function __construct(array $urlItemInfos) {
    $this->urlItemInfos = array_values($urlItemInfos);
  }

  public function filter(Callable $callable): self {
    $urlItemInfos = array_filter($this->urlItemInfos, $callable);
    return new self($urlItemInfos);
  }

  public function getLinks(): self {
    return $this->filter(fn(UrlItemInfo $info) => $info->urlItem instanceof LinkUrl);
  }

  public function getImages(): self {
    return $this->filter(fn(UrlItemInfo $info) => $info->urlItem instanceof ImageUrl);
  }

  public function urlItems(): UrlItemBag {
    $urlItems = array_map(fn(UrlItemInfo $info) => $info->urlItem, $this->urlItemInfos);
    return new UrlItemBag($urlItems);
  }

  public function anonymize(): self {
    return new self(array_map(
      fn(UrlItemInfo $info) => $info->anonymize(),
      $this->urlItemInfos
    ));
  }

}

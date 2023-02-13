<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url;

final class UrlItemInfoBag {

  /**
   * @var array<string, \Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\UrlItemInfo> $urlItemInfosByUrl
   */
  public readonly array $urlItemInfosByUrl;

  /**
   * @param array<string, \Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\UrlItemInfo> $urlItemInfos
   */
  public function __construct(array $urlItemInfos) {
    $urlItemInfosByUrl = [];
    foreach ($urlItemInfos as $urlItemInfo) {
      $urlItemInfosByUrl[$urlItemInfo->urlItem->url] = $urlItemInfo;
    }
    $this->urlItemInfosByUrl = $urlItemInfosByUrl;
  }

  public function filter(Callable $callable): self {
    $urlItemInfos = array_filter($this->urlItemInfosByUrl, $callable);
    return new self($urlItemInfos);
  }

  public function urlItems(): UrlItemBag {
    $urlItems = array_map(fn(UrlItemInfo $info) => $info->urlItem, $this->urlItemInfosByUrl);
    return new UrlItemBag($urlItems);
  }

  public function forUrl(mixed $url): UrlItemInfo {
    return $this->urlItemInfosByUrl[$url]
      ?? throw new \UnexpectedValueException("No such key: $url");
  }

}

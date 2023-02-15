<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer2\Result\Url;

/**
 * Build UrlItemInfoBag.
 *
 * All UrlItems are known in advance.
 * Does not allow to add them afterwards.
 * Intentionally may contain UrlItems without a match.
 * We reasonably assume that no link and image share the same url.
 */
final class UrlItemInfoBagBuilder {

  /**
   * @param array<string, \Geeks4change\UntrackEmailAnalyzer\Analyzer2\Result\Url\UrlItemInfoBuilder> $urlItemInfoBuildersByUrl
   */
  protected function __construct(
    protected readonly array $urlItemInfoBuildersByUrl,
  ) {}

  public static function fromUrlItemBag(UrlItemBag $urlItemBag): self {
    return (new self([]))->withUrlItemBag($urlItemBag);
  }

  public function withUrlItemBag(UrlItemBag $urlItemBag): self {
    $urlItemInfoBuildersByUrl = $this->urlItemInfoBuildersByUrl;
    foreach ($urlItemBag->urlItemsByUrl as $urlItem) {
      $url = $urlItem->url;
      // Extend urls if needed in wither.
      $urlItemInfoBuilder = $urlItemInfoBuildersByUrl[$url]
        ?? ($urlItemInfoBuildersByUrl[$url] = UrlItemInfoBuilder::create($urlItem));
    }
    return new self($urlItemInfoBuildersByUrl);
  }

  public static function fromUrlItemInfoBag(UrlItemInfoBag $urlItemInfoBag): self {
    return (new self([]))->withUrlItemInfoBag($urlItemInfoBag);
  }

  public function withUrlItemInfoBag(UrlItemInfoBag $urlItemInfoBag): self {
    $urlItemInfoBuildersByUrl = $this->urlItemInfoBuildersByUrl;
    foreach ($urlItemInfoBag->urlItemInfos as $urlItemInfo) {
      $url = $urlItemInfo->urlItem->url;
      // Extend urls if needed in wither.
      $urlItemInfoBuilder = $urlItemInfoBuildersByUrl[$url]
        ?? ($urlItemInfoBuildersByUrl[$url] = UrlItemInfoBuilder::fromUrlItemInfo($urlItemInfo));
    }
    return new self($urlItemInfoBuildersByUrl);
  }

  public function forUrlItem(UrlItem $urlItem): UrlItemInfoBuilder {
    return $this->forUrl($urlItem->url);
  }

  protected function forUrl(mixed $url): UrlItemInfoBuilder {
    return $this->urlItemInfoBuildersByUrl[$url]
      ?? throw new \UnexpectedValueException("No such key: $url");
  }

  public function freeze(): UrlItemInfoBag {
    // Do not filter for matches.
    $urlItemInfos = array_map(fn(UrlItemInfoBuilder $builder) => $builder->freeze(), $this->urlItemInfoBuildersByUrl);
    return new UrlItemInfoBag($urlItemInfos);
  }

}

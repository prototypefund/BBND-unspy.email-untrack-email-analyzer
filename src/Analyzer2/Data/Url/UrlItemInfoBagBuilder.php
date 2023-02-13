<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url;

use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\UrlItemMatchType\RedirectInfo;
use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\UrlItemMatchType\UrlItemMatchBase;

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
   * @param array<string, \Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\UrlItemInfoBuilder> $urlItemInfoBuildersByUrl
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
    foreach ($urlItemInfoBag->urlItemInfosByUrl as $urlItemInfo) {
      $url = $urlItemInfo->urlItem->url;
      // Extend urls if needed in wither.
      $urlItemInfoBuilder = $urlItemInfoBuildersByUrl[$url]
        ?? ($urlItemInfoBuildersByUrl[$url] = UrlItemInfoBuilder::create($urlItemInfo->urlItem));
      foreach ($urlItemInfo->matches as $match) {
        $urlItemInfoBuilder->addMatch($match);
      }
    }
    return new self($urlItemInfoBuildersByUrl);
  }

  public function addMatch(UrlItem $urlItem, UrlItemMatchBase $match): void {
    // Do not allow to create new urls.
    $urlItemInfoBuilder = $this->urlItemInfoBuildersByUrl[$urlItem->url]
      ?? throw new \UnexpectedValueException("Unexpected: {$urlItem->url}");
    $urlItemInfoBuilder->addMatch($match);
  }

  public function setRedirectInfo(UrlItem $urlItem, RedirectInfo $redirectInfo) {
    $this->forUrl($urlItem->url)->setRedirectInfo($redirectInfo);
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

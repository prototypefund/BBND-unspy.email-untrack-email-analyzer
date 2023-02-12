<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url;

final class UrlItemInfoBagBuilder {

  /**
   * @param array<string, \Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\UrlItemInfoBuilder> $urlItemInfoBuilders
   */
  protected function __construct(
    public readonly array $urlItemInfoBuilders,
  ) {}

  public function fromUrlRedirectBag(UrlRedirectBag $urlRedirectBag) {
    $urlItemInfoBuilders = [];
    foreach ($urlRedirectBag->urlRedirects as $urlRedirect) {
      // We reasonably assume that no link and image share the same url.
      $urlItemInfoBuilders[$urlRedirect->url] = new UrlItemInfoBuilder($urlRedirect);
    }
    return new self($urlItemInfoBuilders);
  }

  public function addMatch(UrlRedirect $urlRedirect, string $matcherId, UrlItemMatchType $type) {
    $urlItemInfoBuilder = $this->urlItemInfoBuilders[$urlRedirect->url]
      ?? throw new \UnexpectedValueException("Unexpected: {$urlRedirect->url}");
    $urlItemInfoBuilder->addMatch($matcherId, $type);
  }

  public function freeze(): UrlItemInfoBag {
    $urlItemInfos = array_map(fn(UrlItemInfoBuilder $builder) => $builder->freeze(), $this->urlItemInfoBuilders);
    return new UrlItemInfoBag($urlItemInfos);
  }

}

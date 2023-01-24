<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails;

use loophp\collection\Collection;

/**
 * LinkAndImageUrlListPerProviderBuilder
 */
final class LinkAndImageUrlListPerProviderBuilder {

  /**
   * @var array<\Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\LinkAndImageUrlListBuilder> $perProvider
   */
  protected array $perProvider = [];

  public function add(string $providerName, LinkAndImageEnum $urlsType, string $url) {
    $typedUrlsBuilder = $this->perProvider[$providerName]
      ?? ($this->perProvider[$providerName] = new LinkAndImageUrlListBuilder());
    $typedUrlsBuilder->add($urlsType, $url);
  }

  public function freeze(): LinkAndImageUrlListPerProvider {
    $typedUrlListsPerProvider = Collection::fromIterable($this->perProvider)
      ->map(fn(LinkAndImageUrlListBuilder $builder) => $builder->freeze())
      ->all(FALSE);
    return new LinkAndImageUrlListPerProvider($typedUrlListsPerProvider);
  }

}

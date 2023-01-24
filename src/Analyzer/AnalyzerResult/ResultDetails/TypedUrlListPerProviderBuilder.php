<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails;

use loophp\collection\Collection;

/**
 * LinkAndImageUrlListPerProviderBuilder
 */
final class TypedUrlListPerProviderBuilder {

  /**
   * @var array<\Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\TypedUrlListBuilder> $perProvider
   */
  protected array $perProvider = [];

  public function add(string $providerName, UrlTypeEnum $urlsType, string $url) {
    $typedUrlsBuilder = $this->perProvider[$providerName]
      ?? ($this->perProvider[$providerName] = new TypedUrlListBuilder());
    $typedUrlsBuilder->add($urlsType, $url);
  }

  public function freeze(): TypedUrlListPerProvider {
    $typedUrlListsPerProvider = Collection::fromIterable($this->perProvider)
      ->map(fn(TypedUrlListBuilder $builder) => $builder->freeze())
      ->all(FALSE);
    return new TypedUrlListPerProvider($typedUrlListsPerProvider);
  }

}

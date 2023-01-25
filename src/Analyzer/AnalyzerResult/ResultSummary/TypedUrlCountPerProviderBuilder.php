<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultSummary;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\UrlTypeEnum;
use loophp\collection\Collection;

final class TypedUrlCountPerProviderBuilder {

  /**
   * @param array<string, \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultSummary\TypedUrlCountBuilder> $perProvider
   */
  protected array $perProvider = [];

  public function add(string $providerId, UrlTypeEnum $urlType, int $count) {
    $subBuilder = $this->perProvider[$providerId]
      ?? ($this->perProvider[$providerId] = new TypedUrlCountBuilder());
    $subBuilder->add($urlType, $count);
  }

  public function freeze(): TypedUrlCountPerProvider {
    $perProvider = Collection::fromIterable($this->perProvider)
      ->map(fn(TypedUrlCountBuilder $builder) => $builder->freeze())
      ->all(FALSE);
    return new TypedUrlCountPerProvider($perProvider);
  }

}

<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultSummary;

use loophp\collection\Collection;

final class HeaderMatchSummaryPerProviderBuilder {

  protected array $perProvider = [];

  public function add(string $providerId, string $name, bool $isMatch) {
    $headerMatchSummary = $this->perProvider[$providerId]
      ?? ($this->perProvider[$providerId] = new HeaderMatchSummaryBuilder());
    $headerMatchSummary->add($name, $isMatch);
  }

  public function freeze(): HeaderMatchSummaryPerProvider {
    $perProvider = Collection::fromIterable($this->perProvider)
      ->map(fn(HeaderMatchSummaryBuilder $builder) => $builder->freeze())
      ->all(FALSE);
    return new HeaderMatchSummaryPerProvider($perProvider);
  }

}

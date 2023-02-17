<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\Result\Header;

final class HeaderItemInfo {

  /**
   * @param list<\Geeks4change\UntrackEmailAnalyzer\Analyzer\Result\Header\HeaderItemMatch> $matches
   */
  public function __construct(
    public readonly HeaderItem $headerItem,
    public readonly array $matches,
  ) {}

  public function anonymize(): self {
    return new self($this->headerItem->anonymize(), $this->matches);
  }

  public function forProviderIds(array $providerIds): self {
    $builder = new HeaderItemInfoBuilder($this->headerItem);
    foreach ($this->matches as $match) {
      if (in_array($match->providerId, $providerIds)) {
        $builder->addMatch($match);
      }
    }
    return $builder->freeze();
  }

  public function getPositivelyMatchingProviderIds(): array {
    $providerIds = [];
    foreach ($this->matches as $match) {
      if ($match->isMatch) {
        $providerIds[] = $match->providerId;
      }
    }
    return $providerIds;
  }

}

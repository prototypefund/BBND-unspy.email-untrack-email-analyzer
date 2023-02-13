<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Header;

final class HeaderItemInfoBag {

  /**
   * @param list<\Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Header\HeaderItemInfo> $infos
   */
  public function __construct(
    public readonly array $infos,
  ) {}

  public function anonymize(): self {
    return new self(array_map(
      fn(HeaderItemInfo $info) => $info->anonymize(),
      $this->infos
    ));
  }

  public function getProviderIds(): array {
    return array_keys($this->getSummary());
  }

  public function getMatchingHeaderNames(string $providerId): array {
    return $this->getSummary()[$providerId]['match'] ?? [];
  }

  public function getNonMatchingHeaderNames(string $providerId): array {
    return $this->getSummary()[$providerId]['nomatch'] ?? [];
  }

  protected function getSummary(): array {
    $summary = [];
    foreach ($this->infos as $info) {
      foreach ($info->matches as $matcherId => $match) {
        $summary += [$matcherId => [[], []]];
        $index = $match->isMatch ? 'match' : 'nomatch';
        $summary[$matcherId][$index][] = $info->headerItem->name;
      }
    }
    return $summary;
  }

}

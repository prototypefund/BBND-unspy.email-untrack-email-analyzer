<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\Result\Header;

final class HeaderItemInfoBag {

  /**
   * @param HeaderItemInfo[] $infos
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
    $keys = array_keys($this->getSummary());
    return $keys;
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
      foreach ($info->matches as $match) {
        $summary += [$match->providerId => [[], []]];
        $index = $match->isMatch ? 'match' : 'nomatch';
        $summary[$match->providerId][$index][] = $info->headerItem->name;
      }
    }
    return $summary;
  }

}

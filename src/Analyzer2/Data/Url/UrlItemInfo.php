<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url;

use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\UrlItemMatchType\UrlItemMatchType;
use loophp\collection\Collection;

final class UrlItemInfo {

  /**
   * @param list<\Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\UrlItemMatch> $matches
   */
  public function __construct(
    public readonly UrlItem $urlItem,
    public readonly array   $matches,
  ) {}

  public function hasMatchOfType(UrlItemMatchType $type): bool {
    return !Collection::fromIterable($this->matches)
      ->filter(fn(UrlItemMatch $match) => $match->type === $type)
      ->isEmpty();
  }

}

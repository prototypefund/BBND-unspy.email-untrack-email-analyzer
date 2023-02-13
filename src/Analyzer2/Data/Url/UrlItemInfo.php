<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url;

use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\UrlItemMatchType\RedirectInfo;
use loophp\collection\Collection;

final class UrlItemInfo {

  /**
   * @param list<\Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\UrlItemMatchType\UrlItemMatchBase> $matches
   */
  public function __construct(
    public readonly UrlItem       $urlItem,
    public readonly ?RedirectInfo $redirectInfo,
    public readonly ?array        $technicalUrlMatchesById,
    public readonly array         $matches,
  ) {}

  public function filterMatches(Callable $callable): array {
    return Collection::fromIterable($this->matches)
      ->filter($callable)
      ->all(FALSE);
  }

}

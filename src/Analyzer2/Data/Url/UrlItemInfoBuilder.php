<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url;

use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\UrlItemMatchType\UrlItemMatchBase;

final class UrlItemInfoBuilder {

  /**
   * @var list<\Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\UrlItemMatchType\UrlItemMatchBase> $matches
   */
  protected array $matches = [];

  public function __construct(
    protected UrlItem $urlItem,
  ) {}

  public function addMatch(UrlItemMatchBase $match): void {
    $this->matches[] = $match;
  }

  public function freeze(): UrlItemInfo {
    return new UrlItemInfo($this->urlItem, $this->matches);
  }

}

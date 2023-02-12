<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url;

final class UrlItemInfoBuilder {

  /**
   * @var list<\Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\UrlItemMatch> $matches
   */
  protected array $matches = [];

  public function __construct(
    protected UrlItem $urlItem,
  ) {}

  public function addMatch(UrlItemMatch $match): void {
    $this->matches[] = $match;
  }

  public function addCreateMatch(string $matcherId, UrlItemMatchType $type): void {
    $this->matches[] = new UrlItemMatch($matcherId, $type);
  }

  public function freeze(): UrlItemInfo {
    return new UrlItemInfo($this->urlItem, $this->matches);
  }

}

<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url;

use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\UrlItemMatchType\RedirectInfo;
use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\UrlItemMatchType\UrlItemMatchBase;

final class UrlItemInfoBuilder {

  /**
   * @param list<\Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\UrlItemMatchType\UrlItemMatchBase> $matches
   */
  protected function __construct(
    public readonly UrlItem $urlItem,
    protected ?RedirectInfo     $redirectInfo,
    protected array             $matches,
  ) {}

  public static function create(UrlItem $urlItem): self {
    return new self($urlItem, NULL, []);
  }

  public static function fromUrlItemInfo(UrlItemInfo $urlItemInfo): self {
    return new self($urlItemInfo->urlItem, $urlItemInfo->redirectInfo, $urlItemInfo->matches);
  }

  public function addMatch(UrlItemMatchBase $match): void {
    $this->matches[] = $match;
  }

  public function setRedirectInfo(RedirectInfo $redirectInfo): void {
    if ($this->redirectInfo) {
      throw new \UnexpectedValueException('Can only set once.');
    }
    $this->redirectInfo = $redirectInfo;
  }

  public function freeze(): UrlItemInfo {
    return new UrlItemInfo($this->urlItem, $this->redirectInfo, $this->matches);
  }

}

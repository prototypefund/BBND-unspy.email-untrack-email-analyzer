<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\Result\Url;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\Result\Url\Analytics\AnalyticsMatch;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\Result\Url\Match\ProviderMatch;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\Result\Url\Redirect\RedirectInfo;
use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\UrlItemMatch\TechnicalUrlMatch;

final class UrlItemInfoBuilder {

  /**
   * @param array<string, \Geeks4change\UntrackEmailAnalyzer\Analyzer\Result\Url\Match\ProviderMatch> $matchesById
   */
  protected function __construct(
    public readonly UrlItem   $urlItem,
    protected ?RedirectInfo   $redirectInfo,
    protected ?AnalyticsMatch $analyticsInfo,
    protected ?array          $matchesById,
  ) {}

  public static function create(UrlItem $urlItem): self {
    return new self($urlItem, NULL, NULL, []);
  }

  public static function fromUrlItemInfo(UrlItemInfo $urlItemInfo): self {
    return new self(
      $urlItemInfo->urlItem,
      $urlItemInfo->redirectInfo,
      $urlItemInfo->analyticsInfo,
      $urlItemInfo->matchesById,
    );
  }

  public function addMatch(ProviderMatch $match) {
    // @fixme Replaces techUrlMatch, useTrackingUrlMatch, byDomainMatch.
    if (isset($this->matchesById[$match->providerId])) {
      throw new \UnexpectedValueException('Can only set once.');
    }
    $this->matchesById[$match->providerId] = $match;
  }

  public function setRedirectInfo(RedirectInfo $redirectInfo): void {
    if (isset($this->redirectInfo)) {
      throw new \UnexpectedValueException('Can only set once.');
    }
    $this->redirectInfo = $redirectInfo;
  }

  public function setAnalyticsInfo(AnalyticsMatch $analyticsInfo): void {
    if ($this->analyticsInfo) {
      throw new \UnexpectedValueException('Can only set once.');
    }
    $this->analyticsInfo = $analyticsInfo;
  }

  public function freeze(): UrlItemInfo {
    return new UrlItemInfo(
      $this->urlItem,
      $this->redirectInfo,
      $this->analyticsInfo,
      $this->matchesById,
    );
  }

}

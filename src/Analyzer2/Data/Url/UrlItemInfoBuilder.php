<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url;

use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\UrlItemMatchType\AnalyticsInfo;
use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\UrlItemMatchType\RedirectInfo;
use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\UrlItemMatchType\TechnicalUrlMatch;

final class UrlItemInfoBuilder {

  /**
   * @param array<string, \Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\UrlItemMatchType\TechnicalUrlMatch> $technicalUrlMatchesById
   * @param array<string, \Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\UrlItemMatchType\UserTrackingUrlMatch> $userTrackingUrlMatchesById
   * @param array<string, \Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\UrlItemMatchType\ByDomainUrlMatch> $byDomainUrlMatchesById
   */
  protected function __construct(
    public readonly UrlItem  $urlItem,
    protected ?RedirectInfo  $redirectInfo,
    protected ?AnalyticsInfo $analyticsInfo,
    protected ?array         $technicalUrlMatchesById,
    protected ?array         $userTrackingUrlMatchesById,
    protected ?array         $byDomainUrlMatchesById,
  ) {}

  public static function create(UrlItem $urlItem): self {
    return new self($urlItem, NULL, NULL, NULL, NULL, NULL, []);
  }

  public static function fromUrlItemInfo(UrlItemInfo $urlItemInfo): self {
    return new self(
      $urlItemInfo->urlItem,
      $urlItemInfo->redirectInfo,
      $urlItemInfo->analyticsInfo,
      $urlItemInfo->technicalUrlMatchesById,
      $urlItemInfo->userTrackingUrlMatchesById,
      $urlItemInfo->byDomainUrlMatchesById,
    );
  }

  public function addTechnicalUrlMatch(TechnicalUrlMatch $match): void {
    if (isset($this->technicalUrlMatchesById[$match->matcherId])) {
      throw new \UnexpectedValueException('Can only set once.');
    }
    $this->technicalUrlMatchesById[$match->matcherId] = $match;
  }

  public function addUserTrackingUrlMatch(UrlItemMatchType\UserTrackingUrlMatch $match) {
    if (isset($this->userTrackingUrlMatchesById[$match->matcherId])) {
      throw new \UnexpectedValueException('Can only set once.');
    }
    $this->userTrackingUrlMatchesById[$match->matcherId] = $match;
  }

  public function addByDomainUrlMatch(UrlItemMatchType\ByDomainUrlMatch $match) {
    if (isset($this->byDomainUrlMatchesById[$match->matcherId])) {
      throw new \UnexpectedValueException('Can only set once.');
    }
    $this->byDomainUrlMatchesById[$match->matcherId] = $match;
  }

  public function setRedirectInfo(RedirectInfo $redirectInfo): void {
    if (isset($this->redirectInfo)) {
      throw new \UnexpectedValueException('Can only set once.');
    }
    $this->redirectInfo = $redirectInfo;
  }

  public function setAnalyticsInfo(AnalyticsInfo $analyticsInfo): void {
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
      $this->technicalUrlMatchesById,
      $this->userTrackingUrlMatchesById,
      $this->byDomainUrlMatchesById,
    );
  }

}

<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url;

use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\Analytics\AnalyticsMatch;
use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\Match\ProviderMatch;
use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\Redirect\RedirectInfo;
use loophp\collection\Collection;

final class UrlItemInfo {

  /**
   * @param array<string, \Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\Match\ProviderMatch> $matchesById
   */
  public function __construct(
    public readonly UrlItem         $urlItem,
    public readonly ?RedirectInfo   $redirectInfo,
    public readonly ?AnalyticsMatch $analyticsInfo,
    public readonly ?array          $matchesById,
  ) {}

  public function anonymize(): self {
    return new self(
      $this->urlItem->anonymize(),
      $this->redirectInfo?->anonymize(),
      $this->analyticsInfo,
      $this->matchesById,
    );
  }

  public function getNoRedirectCheckProviderIds(): array {
    return Collection::fromIterable($this->matchesById)
      ->filter(fn(ProviderMatch $match) => $match->noRedirectCheck)
      ->keys()
      ->all();
  }

  public function getUserTrackingProviderIds(): array {
    return Collection::fromIterable($this->matchesById)
      ->filter(fn(ProviderMatch $match) => $match->isUserTracking)
      ->keys()
      ->all();
  }

}

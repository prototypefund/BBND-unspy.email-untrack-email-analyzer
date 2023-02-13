<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\UrlItemMatchType;

abstract class UrlItemMatchBase {

  public function isUserTracking(): bool {
    return $this instanceof UserTrackingMatch;
  }

  public function isUnsubscribe(): bool {
    return $this instanceof TechnicalUrlMatch;
  }

  public function isAnalytics(): bool {
    return $this instanceof Analytics;
  }

}

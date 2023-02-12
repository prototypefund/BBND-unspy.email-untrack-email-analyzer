<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\UrlItemMatchType;

/**
 * Sort of algebraic type.
 */
abstract class UrlItemMatchType {

  protected static UserTracking $userTracking;

  protected static Unsubscribe $unsubscribe;

  public static function UserTracking(): UserTracking {
    return static::$userTracking
      ?? (static::$userTracking = new UserTracking());
  }

  public function isUserTracking(): bool {
    return $this instanceof UserTracking;
  }

  public static function Unsubscribe(): Unsubscribe {
    return static::$unsubscribe
      ?? (static::$unsubscribe = new Unsubscribe());
  }

  public function isUnsubscribe(): bool {
    return $this instanceof Unsubscribe;
  }

  public static function Analytics(AnalyticsMatchType $type, array $keys): Analytics {
    return new Analytics($type, $keys);
  }

  public function isAnalytics(): bool {
    return $this instanceof Analytics;
  }

}

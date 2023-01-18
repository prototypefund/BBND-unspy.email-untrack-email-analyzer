<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Utility;

final class Max {

  /**
   * Get maximum without WTF.
   *
   * We want max(0, ...[]) to be 0, not error.
   */
  public static function max(...$values): mixed {
    return max($values);
  }

}

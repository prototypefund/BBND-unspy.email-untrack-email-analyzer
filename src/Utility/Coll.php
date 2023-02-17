<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Utility;

use loophp\collection\Collection;

final class Coll {

  public static function rekey(iterable $iterable, callable $callable) {
    return Collection::fromIterable($iterable)
      ->associate(fn($key, $value) => $callable($value))
      ->all(false);
  }

}

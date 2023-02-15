<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Utility;

final class ObjectToArray {

  public static function convert(mixed $value): mixed {
    if (is_object($value)) {
      return array_map(self::convert(...), get_object_vars($value));
    }
    elseif (is_array($value)) {
      return array_map(self::convert(...), $value);
    }
    else {
      return $value;
    }
  }

}

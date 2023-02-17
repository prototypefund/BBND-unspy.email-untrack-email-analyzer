<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Utility;

final class Extract {

  public static function angleBrackets(string $value): array {
    preg_match_all('~<(.*?)>~u', $value, $matches);
    $extracted = $matches[1] ?? [];
    return $extracted;
  }

}

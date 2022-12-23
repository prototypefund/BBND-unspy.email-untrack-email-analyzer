<?php

declare(strict_types=1);

namespace Geeks4change\BbndAnalyzer\Utility;

/**
 * Helper for array map including keys.
 *
 * Trying to do this with array_map($closure, $values, $keys) results in loss
 * of keys.
 */
final class ArrayTool {

  protected array $array;

  private function __construct(array $array) {
    $this->array = $array;
  }

  public static function create(array $array): self {
    return new self($array);
  }

  public function map(\Closure $closure): array {
    $result = [];
    foreach ($this->array as $key => $value) {
      $result[$key] = $closure($value, $key);
    }
    return $result;
  }
}

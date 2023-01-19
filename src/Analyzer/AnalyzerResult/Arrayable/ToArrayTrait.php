<?php

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\Arrayable;

use loophp\collection\Collection;

trait ToArrayTrait {

  public function toArray(): array {
    return Collection::fromIterable(get_object_vars($this))
      ->map($this->valuesToArray(...))
      ->all(FALSE);
  }

  private function valuesToArray($value) {
    if ($value instanceof ToArrayInterface) {
      return $value->toArray();
    }
    elseif (is_array($value)) {
      return Collection::fromIterable($value)
        ->map($this->valuesToArray(...))
        ->all(FALSE);
    }
    elseif (method_exists($value, 'value') && is_string($result = $value->value)) {
      // Backed enum.
      return $result;
    }
    elseif ($value instanceof \Stringable) {
      return (string)$value;
    }
    else {
      return $value;
    }
  }

}

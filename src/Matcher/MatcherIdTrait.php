<?php

namespace Geeks4change\UntrackEmailAnalyzer\Matcher;

trait MatcherIdTrait {

  protected function getId(): string {
    $class = get_class($this);
    $parts = explode('\\', $class);
    $reverseParts = array_reverse($parts);
    return $reverseParts[1];
  }

}

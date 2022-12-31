<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Utility;

final class PrintCollector {

  protected array $lines;

  public function add(string $line) {
    $this->lines[] = $line;
  }

  public function all(): string {
    return implode("\n", $this->lines);
  }

}

<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Header;

final class HeaderItem {

  protected function __construct(
    public readonly string $name,
    public readonly string $value,
  ) {}

  public static function create(string $name, string $value) {
    return new self(strtolower($name), $value);
  }

}

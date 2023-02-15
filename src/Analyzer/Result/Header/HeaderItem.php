<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\Result\Header;

use Geeks4change\UntrackEmailAnalyzer\Utility\Anon;

final class HeaderItem {

  protected function __construct(
    public readonly string $name,
    public readonly string $value,
  ) {}

  public static function create(string $name, string $value) {
    return new self(strtolower($name), $value);
  }

  public function anonymize(): self {
    return new self($this->name, Anon::header($this->value));

  }

}

<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails;

/**
 * @api Will be serialized in persistent storage, any change needs a migration.
 */
final class CnameInfo {

  /**
   * @var array<string>
   */
  public readonly array $aliases;

  public function __construct(public readonly string $domain, string ...$aliases) {
    $this->aliases = $aliases;
  }

  public function __toString(): string {
    return implode(' => ', [$this->domain, ...$this->aliases]);
  }

}

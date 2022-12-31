<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult;

/**
 * @api Will be serialized in persistent storage, any change needs a migration.
 */
final class DomainAliases {

  protected string $domain;

  /**
   * The resolution list.
   * @var array<string>
   */
  protected array $aliases;

  /**
   * @param string $domain
   * @param string ...$aliases
   */
  public function __construct(string $domain, string ...$aliases) {
    $this->domain = $domain;
    $this->aliases = $aliases;
  }

  /**
   * @return string
   */
  public function getDomain(): string {
    return $this->domain;
  }

  /**
   * @return array<string>
   */
  public function getAliases(): array {
    return $this->aliases;
  }

  /**
   * @return array
   */
  public function getDomainAndAliases(): array {
    return array_merge([$this->domain], $this->aliases);
  }

}

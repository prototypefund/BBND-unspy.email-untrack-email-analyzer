<?php

declare(strict_types=1);

namespace Geeks4change\BbndAnalyzer\Analysis\Summary;

/**
 * @implements \IteratorAggregate<int, \Geeks4change\BbndAnalyzer\Analysis\Summary\DomainAliases>
 *
 * @api Will be serialized in persistent storage, any change needs a migration.
 */
final class DomainAliasesList {

  /**
   * @var array<\Geeks4change\BbndAnalyzer\Analysis\Summary\DomainAliases>
   */
  protected array $domainAliasesList = [];

  public function getIterator(): \ArrayIterator {
    return new \ArrayIterator($this->domainAliasesList);
  }

  public function add(string $domain, string ...$aliases) {
    $this->domainAliasesList[$domain] = new DomainAliases($domain, ...$aliases);
  }

}

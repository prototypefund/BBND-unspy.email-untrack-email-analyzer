<?php

declare(strict_types=1);

namespace Geeks4change\BbndAnalyzer\AnalyzerResult;

/**
 * @implements \IteratorAggregate<int, \Geeks4change\BbndAnalyzer\AnalyzerResult\DomainAliases>
 *
 * @api Will be serialized in persistent storage, any change needs a migration.
 */
final class DomainAliasesList implements \IteratorAggregate {

  /**
   * @var array<\Geeks4change\BbndAnalyzer\AnalyzerResult\DomainAliases>
   */
  protected array $domainAliasesList = [];

  public function getIterator(): \ArrayIterator {
    return new \ArrayIterator($this->domainAliasesList);
  }

  public function add(string $domain, string ...$aliases) {
    $this->domainAliasesList[$domain] = new DomainAliases($domain, ...$aliases);
  }

}

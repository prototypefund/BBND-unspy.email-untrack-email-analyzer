<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails;

/**
 * @implements \IteratorAggregate<int, \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\DomainAliases>
 *
 * @api Will be serialized in persistent storage, any change needs a migration.
 */
final class DomainAliasesList implements \IteratorAggregate {

  /**
   * @param \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\DomainAliases[] $domainAliasesList
   */
  public function __construct(
    public readonly array $domainAliasesList = []
  ) {}

  public static function builder() {
    return new DomainAliasesListBuilder();
  }

  public function getIterator(): \ArrayIterator {
    return new \ArrayIterator($this->domainAliasesList);
  }

}

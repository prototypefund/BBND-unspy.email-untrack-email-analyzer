<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails;

/**
 * @implements \IteratorAggregate< int, \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\CnameChain >
 *
 * @api Will be serialized in persistent storage, any change needs a migration.
 */
final class CnameChainList implements \IteratorAggregate {

  /**
   * @param array<string, \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\CnameChain> $chaneChainsByDomain
   */
  public function __construct(
    public readonly array $chaneChainsByDomain = []
  ) {}

  public static function builder() {
    return new CnameChainListBuilder();
  }

  public function getIterator(): \ArrayIterator {
    return new \ArrayIterator($this->chaneChainsByDomain);
  }

}

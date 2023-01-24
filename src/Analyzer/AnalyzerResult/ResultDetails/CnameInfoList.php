<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails;

/**
 * @implements \IteratorAggregate<int, \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\CnameInfo>
 *
 * @api Will be serialized in persistent storage, any change needs a migration.
 */
final class CnameInfoList implements \IteratorAggregate {

  public function __construct(
    public readonly array $cnameInfos = []
  ) {}

  public static function builder() {
    return new CnameInfoListBuilder();
  }

  public function getIterator(): \ArrayIterator {
    return new \ArrayIterator($this->cnameInfos);
  }

}

<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultSummary;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\UrlTypeEnum;

/**
 * @implements \IteratorAggregate<UrlTypeEnum, int>
 */
final class TypedUrlCount implements \IteratorAggregate {

  public function __construct(
    public readonly int $typeLink,
    public readonly int $typeImage,
  ) {}

  public static function builder(): TypedUrlCountBuilder {
    return new TypedUrlCountBuilder();
  }

  public function getIterator(): \Traversable {
    return (function () {
      yield UrlTypeEnum::Link => $this->typeLink;
      yield UrlTypeEnum::Image => $this->typeImage;
    })();
  }

}

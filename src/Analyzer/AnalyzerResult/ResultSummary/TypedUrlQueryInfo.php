<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultSummary;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\UrlTypeEnum;

/**
 * @implements \IteratorAggregate<UrlTypeEnum, list<\Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultSummary\UrlQueryInfo>>
 */
final class TypedUrlQueryInfo {

  /**
   * @param list<\Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultSummary\UrlQueryInfo> $typeLink
   * @param list<\Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultSummary\UrlQueryInfo> $typeImage
   */
  public function __construct(
    public readonly array $typeLink,
    public readonly array $typeImage,
  ) {}

  public static function builder(): TypedUrlQueryInfoBuilder {
    return new TypedUrlQueryInfoBuilder();
  }

  public function getIterator(): \Traversable {
    return (function () {
      yield UrlTypeEnum::Link => $this->typeLink;
      yield UrlTypeEnum::Image => $this->typeImage;
    })();
  }

}

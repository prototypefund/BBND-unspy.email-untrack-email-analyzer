<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\Arrayable\ToArrayInterface;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\Arrayable\ToArrayTrait;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\TestSummary\TestSummaryInterface;

/**
 * Intermediary result.
 *
 * @implements \IteratorAggregate<\Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\UrlTypeEnum, \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\UrlList>
 */
final class TypedUrlList implements TestSummaryInterface, ToArrayInterface, \IteratorAggregate {

  use ToArrayTrait;

  /**
   * @param \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\UrlList $typeLink
   * @param \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\UrlList $typeImage
   */
  public function __construct(
    public readonly UrlList $typeLink,
    public readonly UrlList $typeImage
  ) {}

  public static function builder(): TypedUrlListBuilder {
    return new TypedUrlListBuilder();
  }

  public function getIterator(): \Traversable {
    return (function () {
      yield UrlTypeEnum::Link => $this->typeLink;
      yield UrlTypeEnum::Image => $this->typeImage;
    })();
  }

  public function getTestSummary(): array {
    return [
      'typeLink' => $this->typeLink->getTestSummary(),
      'typerImage' => $this->typeImage->getTestSummary(),
    ];
  }

}

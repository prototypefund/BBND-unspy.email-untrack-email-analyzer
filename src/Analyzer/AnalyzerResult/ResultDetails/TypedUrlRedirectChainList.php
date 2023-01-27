<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\Arrayable\ToArrayInterface;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\Arrayable\ToArrayTrait;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\TestSummary\TestSummaryInterface;

/**
 * @implements \IteratorAggregate<UrlTypeEnum, \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\UrlRedirectChainList>
 */
final class TypedUrlRedirectChainList  implements TestSummaryInterface, ToArrayInterface, \IteratorAggregate {

  use ToArrayTrait;

  public function __construct(
    public readonly UrlRedirectChainList $typeLink,
    public readonly UrlRedirectChainList $typeImage
  ) {
  }

  public function getTestSummary(): array {
    return [
      'typeLink' => $this->typeLink->getTestSummary(),
      'typeImage' => $this->typeImage->getTestSummary(),
    ];
  }

  public function getIterator(): \Traversable {
    return (function () {
      yield UrlTypeEnum::Link => $this->typeLink;
      yield UrlTypeEnum::Image => $this->typeImage;
    })();
  }

}

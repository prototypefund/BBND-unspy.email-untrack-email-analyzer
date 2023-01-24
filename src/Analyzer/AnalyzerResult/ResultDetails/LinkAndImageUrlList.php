<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\Arrayable\ToArrayInterface;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\Arrayable\ToArrayTrait;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\TestSummary\TestSummaryInterface;
use Traversable;

/**
 * Intermediary result.
 *
 * @implements \IteratorAggregate<\Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\LinkAndImageEnum, \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\UrlList>
 */
final class LinkAndImageUrlList implements TestSummaryInterface, ToArrayInterface, \IteratorAggregate {

  use ToArrayTrait;

  /**
   * @param \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\UrlList $linkUrlList
   * @param \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\UrlList $imageUrlList
   */
  public function __construct(
    public readonly UrlList $linkUrlList,
    public readonly UrlList $imageUrlList
  ) {}

  public static function builder(): LinkAndImageUrlListBuilder {
    return new LinkAndImageUrlListBuilder();
  }

  public function getIterator(): Traversable {
    return (function () {
      yield LinkAndImageEnum::Link => $this->linkUrlList;
      yield LinkAndImageEnum::Image => $this->imageUrlList;
    })();
  }

  public function getTestSummary(): array {
    return [
      'linkUrlList' => $this->linkUrlList->getTestSummary(),
      'imageUrlList' => $this->imageUrlList->getTestSummary(),
    ];
  }

}

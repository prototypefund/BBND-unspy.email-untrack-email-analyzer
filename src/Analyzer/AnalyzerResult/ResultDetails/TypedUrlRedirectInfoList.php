<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\Arrayable\ToArrayInterface;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\Arrayable\ToArrayTrait;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\TestSummary\TestSummaryInterface;

final class TypedUrlRedirectInfoList  implements TestSummaryInterface, ToArrayInterface {

  use ToArrayTrait;

  public function __construct(
    public readonly UrlRedirectInfoList $typeLink,
    public readonly UrlRedirectInfoList $typeImage
  ) {
  }

  public function getTestSummary(): array {
    return [
      'typeLink' => $this->typeLink->getTestSummary(),
      'typeImage' => $this->typeImage->getTestSummary(),
    ];
  }

}

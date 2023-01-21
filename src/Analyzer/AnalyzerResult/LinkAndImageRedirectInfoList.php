<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\Arrayable\ToArrayInterface;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\Arrayable\ToArrayTrait;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\TestSummary\TestSummaryInterface;

final class LinkAndImageRedirectInfoList  implements TestSummaryInterface, ToArrayInterface {

  use ToArrayTrait;

  public function __construct(
    public readonly UrlRedirectInfoList $linkRedirectInfoList,
    public readonly UrlRedirectInfoList $imageRedirectInfoList
  ) {
  }

  public function getTestSummary(): array {
    return [
      'linkUrlList' => $this->linkRedirectInfoList->getTestSummary(),
      'imageUrlList' => $this->imageRedirectInfoList->getTestSummary(),
    ];
  }

}

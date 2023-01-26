<?php

declare(strict_types=1);
namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\Arrayable\ToArrayInterface;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\Arrayable\ToArrayTrait;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\TestSummary\TestSummaryInterface;
use Geeks4change\UntrackEmailAnalyzer\Utility\PrintCollector;

/**
 * Analysis summary.
 *
 * @api Will be serialized in persistent storage, any change needs a migration.
 */
final class ResultDetails implements TestSummaryInterface, ToArrayInterface {

  use ToArrayTrait;

  public function __construct(
    public readonly DKIMResult                 $dkimResult,
    public readonly HeaderMatchListPerProvider $headersResult,
    public readonly TypedUrlList               $typedUrlList,
    public readonly TypedUrlListPerProvider    $exactMatches,
    public readonly TypedUrlListPerProvider    $domainMatches,
    public readonly UrlList                    $pixelsList,
    public readonly UrlList                    $unsubscribeUrlList,
    public readonly TypedUrlRedirectInfoList   $urlsRedirectInfoList,
    public readonly TypedUrlList               $urlsWithAnalyticsList,
    public readonly CnameInfoList              $cnameInfoList,
  ) {}

  public function getTestSummary(): array {
    return [
      'headersResult' => $this->headersResult->getTestSummary(),
      'allLinkAndImageUrlsList' => $this->typedUrlList->getTestSummary(),
      'exactMatches' => $this->exactMatches->getTestSummary(),
      'domainMatches' => $this->domainMatches->getTestSummary(),
      'pixelsList' => $this->pixelsList->getTestSummary(),
      'urlsWithRedirectList' => $this->urlsRedirectInfoList->getTestSummary(),
      'urlsWithAnalyticsList' => $this->urlsWithAnalyticsList->getTestSummary(),
    ];
  }

}

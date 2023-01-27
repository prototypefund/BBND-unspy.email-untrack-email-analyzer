<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\HeaderMatchListPerProvider;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\ResultDetails;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\TypedUrlList;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\TypedUrlListPerProvider;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\TypedUrlRedirectInfoList;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\UrlList;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultSummary\HeaderMatchSummaryPerProvider;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultSummary\ResultSummary;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultSummary\TypedRedirectCount;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultSummary\TypedUrlCount;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultSummary\TypedUrlCountPerProvider;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultSummary\TypedUrlQueryInfo;
use GuzzleHttp\Psr7\Query;

final class ResultSummaryExtractor {

  public function extractResultSummary(ResultDetails $details): ResultSummary {
    // @fixme

    return new ResultSummary(
      $details->dkimResult,
      $this->summarizeHeader($details->headerMatches),
      $this->summarizeUrls($details->typedUrlList),
      $this->summarizeMatches($details->exactMatches),
      $this->summarizeMatches($details->domainMatches),
      $this->summarizePixels($details->pixelsList),
      $this->summarizeRedirects($details->urlsRedirectInfoList),
      $this->summarizeAnalytics($details->urlsWithAnalyticsList),
      $details->cnameInfoList,
    );
  }

  protected function summarizeHeader(HeaderMatchListPerProvider $headersResult): HeaderMatchSummaryPerProvider {
    $builder = HeaderMatchSummaryPerProvider::builder();
    foreach ($headersResult->headersMatchList as $providerId => $headersMatch) {
      foreach ($headersMatch->matches as $match) {
        $builder->add($providerId, $match->headerName, $match->isMatch);
      }
    }
    return $builder->freeze();
  }

  protected function summarizeUrls(TypedUrlList $typedUrlList): TypedUrlCount {
    $builder = TypedUrlCount::builder();
    /** @var \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\UrlTypeEnum $urlType */
    foreach ($typedUrlList as $urlType => $urlList) {
      $builder->add($urlType, $urlList->count());
    }
    return $builder->freeze();
  }

  protected function summarizeMatches(TypedUrlListPerProvider $matches): TypedUrlCountPerProvider {
    $builder = TypedUrlCountPerProvider::builder();
    foreach ($matches->perProvider as $providerId => $typedUrlList) {
      /** @var \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\UrlTypeEnum $urlType */
      foreach ($typedUrlList as $urlType => $urlList) {
        $builder->add($providerId, $urlType, $urlList->count());
      }
    }
    return $builder->freeze();
  }

  protected function summarizePixels(UrlList $pixelsList): int {
    return $pixelsList->count();
  }

  protected function summarizeRedirects(TypedUrlRedirectInfoList $urlsRedirectDetailsList): TypedRedirectCount {
    $builder = TypedRedirectCount::builder();
    /** @var \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\UrlTypeEnum $urlType */
    foreach ($urlsRedirectDetailsList as $urlType => $urlRedirectDetailsList) {
      foreach ($urlRedirectDetailsList as $urlRedirectDetails) {
        if ($urlRedirectDetails->redirectUrls) {
          $builder->add($urlType);
        }
      }
    }
    return $builder->freeze();
  }

  protected function summarizeAnalytics(TypedUrlList $typedUrlList): TypedUrlQueryInfo {
    $allAnalyticsKeys = (new AnalyticsKeyInfo)->getKeys();
    $builder = TypedUrlQueryInfo::builder();
    /** @var \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\UrlTypeEnum $urlType */
    foreach ($typedUrlList as $urlType => $urlList) {
      foreach ($urlList as $urlItem) {
        $queryKeys = array_keys(Query::parse($urlItem->getUrlObject()->getQuery()));
        $analyticsKeys = array_intersect($queryKeys, $allAnalyticsKeys);
        $otherKeys = array_diff($queryKeys, $analyticsKeys);
        $builder->add($urlType, $analyticsKeys, $otherKeys);
      }
    }
    return $builder->freeze();
  }


}

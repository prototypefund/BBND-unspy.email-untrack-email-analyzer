<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\NewsletterServicesMatcher\HeadersMatcher;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\HeaderSingleResult;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\HeadersResult;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\HeadersResultPerService;
use Geeks4change\UntrackEmailAnalyzer\Globals;
use ZBateson\MailMimeParser\Message;

final class AllServicesHeadersMatcher {
  public function matchHeaders(Message $message): HeadersResult {
    $headerResultItems = [];
    /** @var \Geeks4change\UntrackEmailAnalyzer\Analyzer\NewsletterServicesMatcher\ServiceMatcherProvider $serviceMatcher */
    foreach (Globals::get()->getServiceMatcherProviderRepository()->getServiceMatcherProviderCollection() as $serviceMatcher) {
      $headerMatchSummaryList = [];
      foreach ($serviceMatcher->getHeadersMatchers() as $headersMatcher) {
        $isMatch = $headersMatcher->matchHeaders($message);
        $headerMatchSummaryList[] = new HeaderSingleResult($headersMatcher->getName(), $isMatch);
      }
      $headersResultPerService = new HeadersResultPerService($serviceMatcher->getName(), $headerMatchSummaryList);
      if ($headersResultPerService->isNonEmpty()) {
        $headerResultItems[$headersResultPerService->getServiceName()] = $headersResultPerService;
      }
    }
    return new HeadersResult($headerResultItems);
  }

}

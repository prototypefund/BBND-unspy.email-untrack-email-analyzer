<?php

declare(strict_types=1);

namespace Geeks4change\BbndAnalyzer\Analyzer\NewsletterServicesMatcher\HeadersMatcher;

use Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult\HeaderSingleResult;
use Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult\HeadersResult;
use Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult\HeadersResultPerService;
use Geeks4change\BbndAnalyzer\Globals;
use ZBateson\MailMimeParser\Message;

final class HeadersAllServicesMatcher {
  public function matchHeaders(Message $message): HeadersResult {
    $headerResult = new HeadersResult();
    /** @var \Geeks4change\BbndAnalyzer\Analyzer\NewsletterServicesMatcher\ServiceMatcherProvider $serviceMatcher */
    foreach (Globals::get()->getServiceMatcherProviderRepository()->getServiceMatcherProviderCollection() as $serviceMatcher) {
      $headerMatchSummaryList = [];
      foreach ($serviceMatcher->getHeadersMatchers() as $headersMatcher) {
        $isMatch = $headersMatcher->matchHeaders($message);
        $headerMatchSummaryList[] = new HeaderSingleResult($headersMatcher->getName(), $isMatch);
      }
      $headersResultPerService = new HeadersResultPerService($serviceMatcher->getName(), $headerMatchSummaryList);
      if ($headersResultPerService->isNonEmpty()) {
        $headerResult->add($headersResultPerService);
      }
    }
    return $headerResult;
  }

}

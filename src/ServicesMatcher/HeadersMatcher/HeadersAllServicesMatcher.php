<?php

declare(strict_types=1);

namespace Geeks4change\BbndAnalyzer\ServicesMatcher\HeadersMatcher;

use Geeks4change\BbndAnalyzer\AnalyzerResult\HeaderMatchSummary;
use Geeks4change\BbndAnalyzer\AnalyzerResult\HeadersResult;
use Geeks4change\BbndAnalyzer\AnalyzerResult\HeadersSummaryPerService;
use Geeks4change\BbndAnalyzer\Globals;
use ZBateson\MailMimeParser\Message;

final class HeadersAllServicesMatcher {
  public function matchHeaders(Message $message): HeadersResult {
    $headerSummaryPerServiceList = [];
    /** @var \Geeks4change\BbndAnalyzer\ServicesMatcher\ServiceMatcherProvider $serviceMatcher */
    foreach (Globals::get()->getServiceMatcherProviderRepository()->getServiceMatcherProviderCollection() as $serviceMatcher) {
      $headerMatchSummaryList = [];
      foreach ($serviceMatcher->getHeadersMatchers() as $headersMatcher) {
        $isMatch = $headersMatcher->matchHeaders($message);
        $headerMatchSummaryList[] = new HeaderMatchSummary($headersMatcher->getName(), $isMatch);
      }
      $headerSummaryPerServiceList[] = new HeadersSummaryPerService($serviceMatcher->getName(), $headerMatchSummaryList);
    }
    return new HeadersResult($headerSummaryPerServiceList);
  }

}

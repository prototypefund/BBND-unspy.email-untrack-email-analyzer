<?php

declare(strict_types=1);

namespace Geeks4change\BbndAnalyzer\ServicesMatcher\HeadersMatcher;

use Geeks4change\BbndAnalyzer\AnalyzerResult\HeaderMatchSummary;
use Geeks4change\BbndAnalyzer\AnalyzerResult\HeaderResult;
use Geeks4change\BbndAnalyzer\AnalyzerResult\HeaderSummaryPerService;
use Geeks4change\BbndAnalyzer\Globals;
use ZBateson\MailMimeParser\Message;

final class HeadersMatcher {
  public function matchHeaders(Message $message): HeaderResult {
    $headerSummaryPerServiceList = [];
    /** @var \Geeks4change\BbndAnalyzer\ServicesMatcher\ServiceMatcherProvider $serviceMatcher */
    foreach (Globals::get()->getServiceMatcherProviderRepository()->getServiceMatcherProviderCollection() as $serviceMatcher) {
      $headerMatchSummaryList = [];
      foreach ($serviceMatcher->getHeaderMatchers() as $headerPattern) {
        $isMatch = $headerPattern->matchHeader($message);
        $headerMatchSummaryList[] = new HeaderMatchSummary($headerPattern->getName(), $isMatch);
      }
      $headerSummaryPerServiceList[] = new HeaderSummaryPerService($serviceMatcher->getName(), $headerMatchSummaryList);
    }
    return new HeaderResult($headerSummaryPerServiceList);
  }

}

<?php

declare(strict_types=1);

namespace Geeks4change\BbndAnalyzer\Analysis;

use Geeks4change\BbndAnalyzer\Analysis\Summary\HeaderMatchSummary;
use Geeks4change\BbndAnalyzer\Analysis\Summary\HeaderResult;
use Geeks4change\BbndAnalyzer\Analysis\Summary\HeaderSummaryPerService;
use Geeks4change\BbndAnalyzer\Globals;
use ZBateson\MailMimeParser\Message;

final class ServiceHeaderAnalyzer {
  public function analyzeHeaders(Message $message): HeaderResult {
    $headerSummaryPerServiceList = [];
    /** @var \Geeks4change\BbndAnalyzer\Pattern\ToolPattern $serviceMatcher */
    foreach (Globals::get()->getServiceInfoRepository()->getToolPatternCollection() as $serviceMatcher) {
      $headerMatchSummaryList = [];
      foreach ($serviceMatcher->getHeaderPatterns() as $headerPattern) {
        $isMatch = $headerPattern->matchHeader($message);
        $headerMatchSummaryList[] = new HeaderMatchSummary($headerPattern->getName(), $isMatch);
      }
      $headerSummaryPerServiceList[] = new HeaderSummaryPerService($serviceMatcher->getName(), $headerMatchSummaryList);
    }
    return new HeaderResult($headerSummaryPerServiceList);
  }

}

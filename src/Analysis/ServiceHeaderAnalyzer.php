<?php

declare(strict_types=1);

namespace Geeks4change\BbndAnalyzer\Analysis;

use Geeks4change\BbndAnalyzer\Analysis\Summary\HeaderMatchSummary;
use Geeks4change\BbndAnalyzer\Analysis\Summary\HeaderSummary;
use Geeks4change\BbndAnalyzer\Analysis\Summary\HeaderSummaryPerService;
use Geeks4change\BbndAnalyzer\Pattern\ToolPatternRepository;
use ZBateson\MailMimeParser\Message;

final class ServiceHeaderAnalyzer {

  protected ToolPatternRepository $serviceMatcherRepository;

  /**
   * @param \Geeks4change\BbndAnalyzer\Pattern\ToolPatternRepository $serviceMatcherRepository
   */
  public function __construct(ToolPatternRepository $serviceMatcherRepository) {
    $this->serviceMatcherRepository = $serviceMatcherRepository;
  }

  public function analyzeHeaders(Message $message): HeaderSummary {
    $headerSummaryPerServiceList = [];
    /** @var \Geeks4change\BbndAnalyzer\Pattern\ToolPattern $serviceMatcher */
    foreach ($this->serviceMatcherRepository->getToolPatternCollection() as $serviceMatcher) {
      $headerMatchSummaryList = [];
      foreach ($serviceMatcher->getHeaderPatterns() as $headerPattern) {
        $isMatch = $headerPattern->matchHeader($message);
        $headerMatchSummaryList[] = new HeaderMatchSummary($headerPattern, $isMatch);
      }
      $headerSummaryPerServiceList[] = new HeaderSummaryPerService($serviceMatcher->getName(), $headerMatchSummaryList);
    }
    return new HeaderSummary($headerSummaryPerServiceList);
  }

}

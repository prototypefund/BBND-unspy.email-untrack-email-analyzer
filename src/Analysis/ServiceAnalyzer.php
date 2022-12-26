<?php

declare(strict_types=1);

namespace Geeks4change\BbndAnalyzer\Analysis;

use Geeks4change\BbndAnalyzer\Analysis\Summary\HeaderSummary;
use Geeks4change\BbndAnalyzer\Pattern\ToolPatternRepository;
use ZBateson\MailMimeParser\Message;

final class ServiceAnalyzer {

  protected ToolPatternRepository $serviceMatcherRepository;

  public function __construct() {
    $this->serviceMatcherRepository = new ToolPatternRepository();
  }

  public function analyzeHeaders(Message $message): HeaderSummary {
    return (new ServiceHeaderAnalyzer($this->serviceMatcherRepository))->analyzeHeaders($message);
  }

}

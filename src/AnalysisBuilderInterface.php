<?php

namespace Geeks4change\BbndAnalyzer;

use Geeks4change\BbndAnalyzer\Matching\MatchSummary;

interface AnalysisBuilderInterface {

  public function audit(string $message): void;

  public function setIsValid(): bool;

  public function getDebugOutput(): array;

  public function setEmailWithHeaders(string $emailWithHeaders): void;
  public function setMatchSummary(MatchSummary $matchSummary): void;

}

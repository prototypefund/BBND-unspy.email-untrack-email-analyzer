<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ListInfo\ListInfo;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultSummary\ResultSummary;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultVerdict\ResultVerdict;

final class PersistentResult {

  public function __construct(
    public readonly ListInfo      $listInfo,
    public readonly MessageInfo   $messageInfo,
    public readonly ResultVerdict $verdict,
    public readonly ResultSummary $summary,
  ) {}

}

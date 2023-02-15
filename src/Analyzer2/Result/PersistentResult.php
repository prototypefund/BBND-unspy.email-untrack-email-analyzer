<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer2\Result;

use Geeks4change\UntrackEmailAnalyzer\Analyzer2\ResultDetails\ListInfo;
use Geeks4change\UntrackEmailAnalyzer\Analyzer2\ResultDetails\ResultDetails;
use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Verdict\ResultVerdict;

final class PersistentResult {

  public function __construct(
    public readonly ListInfo      $listInfo,
    public readonly MessageInfo   $messageInfo,
    public readonly ResultVerdict $verdict,
    public readonly ResultDetails $details,
  ) {}

}

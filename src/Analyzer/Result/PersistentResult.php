<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\Result;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\Verdict\ResultVerdict;

final class PersistentResult {

  public function __construct(
    public readonly ListInfo      $listInfo,
    public readonly MessageInfo   $messageInfo,
    public readonly ResultVerdict $verdict,
    public readonly ResultDetails $details,
  ) {}

}

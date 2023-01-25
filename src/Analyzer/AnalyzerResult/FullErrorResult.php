<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult;

final class FullErrorResult extends FullResultBase {

  public function getPersistentResult(): PersistentResultBase {
    return new PersistentErrorResult($this->log);
  }

}

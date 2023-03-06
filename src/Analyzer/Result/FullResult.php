<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\Result;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\Verdict\ResultVerdict;

/**
 * Analyzer result.
 *
 * @api Will be serialized in persistent storage, any change needs a migration.
 */
final class FullResult extends Result {

  /**
   * Create persistent result from full result.
   *
   * - Leaves away ResultDetails, as they contain recipient specific links.
   */
  public function getPersistentResult(): PersistentResult {
    return new PersistentResult(
      $this->listInfo,
      $this->messageInfo,
      $this->verdict,
      $this->details->anonymize(),
    );
  }

}

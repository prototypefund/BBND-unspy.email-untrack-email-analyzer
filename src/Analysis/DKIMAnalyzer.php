<?php

declare(strict_types=1);

namespace Geeks4change\BbndAnalyzer\Analysis;

use Geeks4change\BbndAnalyzer\Analysis\Summary\DKIMSummary;
use PHPMailer\DKIMValidator\DKIMException;
use PHPMailer\DKIMValidator\Validator;

final class DKIMAnalyzer {

  public function analyzeDKIM(string $emailWithHeaders): DKIMSummary {
    try {
      $dkimValidator = new Validator($emailWithHeaders);
      $dkimResults = $dkimValidator->validate();
    } catch (DKIMException $e) {
      $dkimResults = [];
    }
    return ($this->extractSummary($dkimResults));
  }

  protected function extractSummary(array $result) {
    return new DKIMSummary(
      $this->extractStatus($result),
      $this->extractSummaryLines($result),
    );
  }
  protected function extractSummaryLines(array $result): array {
    $summaryLines = [];
    foreach ($result as $headerIndex => $messages) {
      $messageLines = [];
      foreach ($messages as $message) {
        $messageLines[] = $message['reason'];
      }
      $summaryLines[] = sprintf('Signature %s: ', $headerIndex+1) . implode(' ', $messageLines);
    }
    return $summaryLines;
  }

  /**
   * Extract status:
   * - green: Any header succeeds validation without problems.
   * - yellow: Any header succeeds validation, but has problems.
   * - red: No validation success.
   */
  protected function extractStatus(array $result): string {
    $headerStatusList = [];
    foreach ($result as $headerIndex => $messages) {
      $headerHasSuccessMessage = $headerHasProblemMessage = FALSE;
      foreach ($messages as $message) {
        $isSuccessMessage = $message['status'] === 'SUCCESS';
        if ($isSuccessMessage) {
          $headerHasSuccessMessage = TRUE;
        }
        else {
          $headerHasProblemMessage = TRUE;
        }
      }
      $headerStatusList[] = $headerHasSuccessMessage ?
        ($headerHasProblemMessage ? 1 : 2) :
        0;
    }
    $messageStatus = max(0, ...$headerStatusList);
    return ['red', 'yellow', 'green'][$messageStatus];
  }



}
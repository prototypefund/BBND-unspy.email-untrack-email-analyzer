<?php

declare(strict_types=1);

namespace Geeks4change\BbndAnalyzer\Utility;

/**
 * DKIMValidator summary tool.
 */
final class DKIMValidatorTool {

  public static function extractSummary(array $result): array {
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
  public static function extractStatus(array $result): string {
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

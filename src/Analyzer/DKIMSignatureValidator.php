<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\DKIMResult;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\DKIMStatusEnum;
use Geeks4change\UntrackEmailAnalyzer\Utility\Max;
use PHPMailer\DKIMValidator\DKIM;
use PHPMailer\DKIMValidator\DKIMException;
use PHPMailer\DKIMValidator\Validator;

final class DKIMSignatureValidator {

  public function validateDKIMSignature(string $emailWithHeaders): DKIMResult {
    try {
      $dkimValidator = $this->createValidator($emailWithHeaders);
      $dkimResults = $dkimValidator->validate();
    } catch (DKIMException $e) {
      $dkimResults = [];
    }
    return ($this->extractSummary($dkimResults));
  }

  public function createValidator(string $emailWithHeaders): Validator {
    // Work around php warning of validator.
    $headersAndBody = explode(DKIM::CRLF . DKIM::CRLF, $emailWithHeaders, 2);
    if (count($headersAndBody) < 2) {
      throw new DKIMException('Can not find header body separator.');
    }
    return new Validator($emailWithHeaders);
  }

  protected function extractSummary(array $result) {
    return new DKIMResult(
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
  protected function extractStatus(array $result): DKIMStatusEnum {
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
    $messageStatus = Max::max(0, ...$headerStatusList);
    return [
      DKIMStatusEnum::Red,
      DKIMStatusEnum::Yellow,
      DKIMStatusEnum::Green
    ][$messageStatus];
  }

}

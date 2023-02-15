<?php

declare(strict_types=1);
namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\Result\DKIM;

enum DKIMStatusEnum: string {
  case Valid = 'valid';
  case ValidWithWarnings = 'valid_with_warnings';
  case Invalid = 'red';

  public function isValid(): bool {
    return match ($this) {
      self::Valid, self::ValidWithWarnings => TRUE,
      self::Invalid => FALSE,
    };
  }

}

<?php

namespace Geeks4change\BbndAnalyzer;

class NullAnalysisBuilder implements AnalysisBuilderInterface {

  public function audit(string $message): void {
  }

  public function isValid(): bool {
    return TRUE;
  }

  public function setEmailWithHeaders(): void {
  }

  public function setNewsletterMetadata(): void {
  }

}

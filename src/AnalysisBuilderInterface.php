<?php

namespace Geeks4change\BbndAnalyzer;

interface AnalysisBuilderInterface {

  public function audit(string $message): void;

  public function isValid(): bool;

  public function setEmailWithHeaders(): void;
  public function setNewsletterMetadata(): void;

}

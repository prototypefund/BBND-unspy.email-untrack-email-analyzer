<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer;

final class AnalyticsKeyInfo {

  protected array $keys;

  public function getKeys(): array {
    return $this->keys
      ?? ($this->keys = $this->createKeys());
  }

  protected function createKeys(): array {
    $providers = explode('|', 'utm_|matomo_|mtm_|piwik_|pk_|');
    $parameters = explode('|', 'source|medium|campaign|term|content|keyword|kwd');
    $keys = [];
    foreach ($providers as $provider) {
      foreach ($parameters as $parameter) {
        $keys[] = "{$provider}{$parameter}";
      }
    }
    return $keys;
  }

}

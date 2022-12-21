<?php

namespace Geeks4change\BbndAnalyzer\Pattern;

final class DomainPattern {

  protected string $domain;

  private function __construct(string $domain) {
    $this->domain = $domain;
  }

  public static function fromItem($value, $key) {
    return new self($value);
  }

  /**
   * @return string
   */
  public function getDomain(): string {
    return $this->domain;
  }

  public function matches(string $host): bool {
    $quotedDomain = preg_quote($this->domain, '#');
    return preg_match("#(?:^|[.]){$quotedDomain}$#ui", $host);
  }

}
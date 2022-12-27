<?php

declare(strict_types=1);

namespace Geeks4change\BbndAnalyzer\Pattern;

use Geeks4change\BbndAnalyzer\Utility\UrlTool;
use Psr\Http\Message\UriInterface;

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

  /**
   * @param array<string> $effectiveHosts
   *
   * @return bool
   */
  public function matches(array $effectiveHosts): bool {
    $quotedDomain = preg_quote($this->domain, '#');
    foreach ($effectiveHosts as $effectiveHost) {
      if (preg_match("#(?:^|[.]){$quotedDomain}$#ui", $effectiveHost)) {
        return TRUE;
      }
    }
    return FALSE;
  }

  public function nowDoMatches(UriInterface $url): bool {
    $quotedDomain = preg_quote($this->domain, '#');
    foreach (UrlTool::getAllDomainAliases($url) as $aliasUrl) {
      if (preg_match("#(?:^|[.]){$quotedDomain}$#ui", $aliasUrl->getHost())) {
        return TRUE;
      }
    }
    return FALSE;
  }

}
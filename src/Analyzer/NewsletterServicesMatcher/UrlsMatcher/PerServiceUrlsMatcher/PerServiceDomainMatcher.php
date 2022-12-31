<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\NewsletterServicesMatcher\UrlsMatcher\PerServiceUrlsMatcher;

use Geeks4change\UntrackEmailAnalyzer\Utility\UrlTool;
use Psr\Http\Message\UriInterface;

final class PerServiceDomainMatcher {

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
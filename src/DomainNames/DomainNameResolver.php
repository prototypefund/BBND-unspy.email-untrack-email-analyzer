<?php

declare(strict_types=1);

namespace Geeks4change\BbndAnalyzer\DomainNames;

final class DomainNameResolver {

  /**
   * @var array<?string, string>
   */
  protected array $cNameMap = [];

  /**
   * @param string $domain
   *
   * @return array<string>
   */
  public function resolve(string $domain, $aliases = []): array {
    $aliases[] = $domain;
    $hop = $this->getCName($domain);

    return $hop ? $this->resolve($hop, $aliases) : $aliases;
  }

  protected function getCName(string $domain) {
    if (!array_key_exists($domain, $this->cNameMap)) {
      $records = dns_get_record($domain, DNS_CNAME);
      $maybeCName = $records[0]['target'] ?? NULL;
      $this->cNameMap[$domain] = $maybeCName;
    }
    return $this->cNameMap[$domain];
  }

}

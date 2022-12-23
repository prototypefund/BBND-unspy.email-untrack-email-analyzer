<?php

declare(strict_types=1);

namespace Geeks4change\BbndAnalyzer\DomainNames;

final class DomainNameResolver {

  protected static self $singleton;

  /**
   * Get singleton to re-use dns queries.
   */
  public static function get(): self {
    if (!isset(self::$singleton)) {
      self::$singleton = new self();
    }
    return self::$singleton;
  }
  /**
   * @var array<?string, string>
   */
  protected array $cNameMap = [];

  /**
   * Get all domain aliases.
   *
   * @param string $domain
   *
   * @return array<string>
   *   All CName aliases, with $domain always as first item.
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

<?php

declare(strict_types=1);

namespace Geeks4change\BbndAnalyzer\DomainAliases;

/**
 * Resolves domain aliases aka CNames.
 */
final class DomainAliasesResolver {

  /**
   * Single alias map.
   *
   * @var array<string, ?string>
   */
  protected array $aliasMap = [];

  /**
   * @var array<string, array<string>>
   */
  protected array $aliasesMap = [];

  /**
   * Get all domain aliases.
   *
   * @param string $domain
   *
   * @return array<string>
   *   All CName aliases, with $domain always as first item.
   */
  public function getAliases(string $domain, $aliases = []): array {
    if (!isset($this->aliasesMap[$domain])) {
      $aliases[] = $domain;
      $hop = $this->getAlias($domain);

      $aliases = $hop ? $this->getAliases($hop, $aliases) : $aliases;
      $this->aliasesMap[$domain] = $aliases;
    }
    return $this->aliasesMap[$domain];
  }

  protected function getAlias(string $domain): ?string {
    if (!array_key_exists($domain, $this->aliasMap)) {
      $records = dns_get_record($domain, DNS_CNAME);
      $maybeCName = $records[0]['target'] ?? NULL;
      $this->aliasMap[$domain] = $maybeCName;
    }
    return $this->aliasMap[$domain];
  }

  public function getAllAliases() {
    return $this->aliasesMap;
  }

}

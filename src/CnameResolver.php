<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer;

/**
 * Resolves domain aliases aka CNames.
 */
final class CnameResolver {

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
  public function getCnameChain(string $domain, $cnames = []): array {
    if (!isset($this->aliasesMap[$domain])) {
      $cnames[] = $domain;
      $hop = $this->getCname($domain);

      $cnames = $hop ? $this->getCnameChain($hop, $cnames) : $cnames;
      $this->aliasesMap[$domain] = $cnames;
    }
    return $this->aliasesMap[$domain];
  }

  protected function getCname(string $domain): ?string {
    if (!array_key_exists($domain, $this->aliasMap)) {
      if (!$domain) {
        $this->aliasMap[$domain] = NULL;
      }
      else {
        // @todo Consider adding retry on error.
        // Add a trailing dot to prevent local resolving.
        $records = dns_get_record("{$domain}.", DNS_CNAME);
        $maybeCName = $records[0]['target'] ?? NULL;
        $this->aliasMap[$domain] = $maybeCName;
      }
    }
    return $this->aliasMap[$domain];
  }

  public function getAllCnameChains() {
    return $this->aliasesMap;
  }

}

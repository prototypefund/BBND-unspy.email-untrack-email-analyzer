<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\Result\CnameChain;

/**
 * @api Will be serialized in persistent storage, any change needs a migration.
 */
final class CnameChain {

  public readonly string $domain;

  /**
   * @var array<string>
   */
  public readonly array $aliasDomains;

  public function __construct(
    string $domain,
    string ...$aliasDomains
  ) {
    $this->domain = $domain;
    $this->aliasDomains = $aliasDomains;
  }

}

<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer2\ResultDetails\CnameChain;

/**
 * @api Will be serialized in persistent storage, any change needs a migration.
 */
final class CnameChain {

  /**
   * @var array<string>
   */
  public readonly array $aliasDomains;

  public function __construct(
    public readonly string $domain,
    string ...$aliasDomains
  ) {
    $this->aliasDomains = $aliasDomains;
  }

}

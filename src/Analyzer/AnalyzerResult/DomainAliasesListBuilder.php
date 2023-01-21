<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult;

final class DomainAliasesListBuilder {

  protected array $domainAliasesList = [];

  public function add(string $domain, string ...$aliases) {
    $this->domainAliasesList[$domain] = new DomainAliases($domain, ...$aliases);
  }

  public function freeze() {
    return new DomainAliasesList($this->domainAliasesList);
  }

}

<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer2\ResultDetails\CnameChain;

final class CnameChainListBuilder {

  protected array $chaneChainsByDomain = [];

  public function add(string $domain, string ...$aliases) {
    $this->chaneChainsByDomain[$domain] = new CnameChain($domain, ...$aliases);
  }

  public function freeze() {
    return new CnameChainList($this->chaneChainsByDomain);
  }

}
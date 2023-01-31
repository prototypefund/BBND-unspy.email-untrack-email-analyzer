<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails;

final class CnameChainListBuilder {

  protected array $chaneChainsByDomain = [];

  public function add(string $domain, string ...$aliases) {
    $this->chaneChainsByDomain[$domain] = new CnameChain($domain, ...$aliases);
  }

  public function freeze() {
    return new CnameChainList($this->chaneChainsByDomain);
  }

}

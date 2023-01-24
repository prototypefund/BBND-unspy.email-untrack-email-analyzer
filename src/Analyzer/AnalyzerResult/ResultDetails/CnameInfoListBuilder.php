<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails;

final class CnameInfoListBuilder {

  protected array $domainAliasesList = [];

  public function add(string $domain, string ...$aliases) {
    $this->domainAliasesList[$domain] = new CnameInfo($domain, ...$aliases);
  }

  public function freeze() {
    return new CnameInfoList($this->domainAliasesList);
  }

}

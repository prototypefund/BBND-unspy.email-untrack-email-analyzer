<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer2;

use Geeks4change\UntrackEmailAnalyzer\Analyzer2\ResultDetails\CnameChain\CnameChainList;
use Geeks4change\UntrackEmailAnalyzer\Analyzer2\ResultDetails\CnameChain\CnameChainListBuilder;
use Geeks4change\UntrackEmailAnalyzer\Globals;

final class CnameDumper {

  public function dumpCnames(): CnameChainList {
    $result = new CnameChainListBuilder();
    foreach (Globals::get()->getDomainAliasesResolver()->getAllAliases() as $aliases) {
      $result->add(...$aliases);
    }
    return $result->freeze();
  }

}

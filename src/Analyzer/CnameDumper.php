<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\Result\CnameChain\CnameChainList;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\Result\CnameChain\CnameChainListBuilder;
use Geeks4change\UntrackEmailAnalyzer\Globals;

final class CnameDumper {

  public function dumpCnames(): CnameChainList {
    $result = new CnameChainListBuilder();
    foreach (Globals::get()->getCnameResolver()->getAllCnameChains() as $cnames) {
      $result->add(...$cnames);
    }
    return $result->freeze();
  }

}

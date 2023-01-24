<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\CnameInfoList;
use Geeks4change\UntrackEmailAnalyzer\Globals;

final class DomainAliasesResultFetcher {

  public function fetch(): CnameInfoList {
    $result = CnameInfoList::builder();
    foreach (Globals::get()->getDomainAliasesResolver()->getAllAliases() as $aliases) {
      $result->add(...$aliases);
    }
    return $result->freeze();
  }

}

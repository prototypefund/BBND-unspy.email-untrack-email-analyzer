<?php

declare(strict_types=1);

namespace Geeks4change\BbndAnalyzer\Analysis;

use Geeks4change\BbndAnalyzer\Analysis\Summary\DomainAliasesList;
use Geeks4change\BbndAnalyzer\Globals;

final class DomainAliasesResultFetcher {

  public function fetch(): DomainAliasesList {
    $result = new DomainAliasesList();
    foreach (Globals::get()->getDomainAliasesResolver()->getAllAliases() as $aliases) {
      $result->add(...$aliases);
    }
    return $result;
  }

}

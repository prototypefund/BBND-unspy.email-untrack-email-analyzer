<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\Result\CnameChain;

use Geeks4change\UntrackEmailAnalyzer\Utility\Coll;

final class CnameChainList {

  public readonly array $cnaneChainsByDomain;

  /**
   * @param CnameChain[] $cnaneChains
   */
  public function __construct(
    array $cnaneChains = []
  ) {
    $cnaneChainsByDomain = Coll::rekey($cnaneChains, fn(CnameChain $cnameChain) => $cnameChain->domain);
    ksort($cnaneChainsByDomain);
    $this->cnaneChainsByDomain = $cnaneChainsByDomain;
  }

}

<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\Result\CnameChain;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\CnameChain;

final class CnameChainList {

  /**
   * @param CnameChain $chaneChainsByDomain
   */
  public function __construct(
    public readonly array $chaneChainsByDomain = []
  ) {}

}

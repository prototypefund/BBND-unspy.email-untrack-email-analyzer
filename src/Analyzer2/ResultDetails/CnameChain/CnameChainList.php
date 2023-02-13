<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer2\ResultDetails\CnameChain;

final class CnameChainList {

  /**
   * @param list< \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\CnameChain > $chaneChainsByDomain
   */
  public function __construct(
    public readonly array $chaneChainsByDomain = []
  ) {}

}

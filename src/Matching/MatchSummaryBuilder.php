<?php

namespace Geeks4change\BbndAnalyzer\Matching;

class MatchSummaryBuilder {

  /**
   * @var array<\Geeks4change\BbndAnalyzer\Matching\MatchByDomain>
   */
  protected array $matchByDomainList = [];

  /**
   * @var array<\Geeks4change\BbndAnalyzer\Matching\MatchByPattern>
   */
  protected array $matchByPatternList = [];

  protected \Closure $constructor;

  /**
   * Nothing to see here. Use MatchSummary::builder.
   */
  public function __construct(\Closure $constructor) {
    $this->constructor = $constructor;
  }

  public function addMatchByDomain(MatchByDomain $match) {
    $this->matchByDomainList[] = $match;
  }

  public function addMatchByPattern(MatchByPattern $match) {
    $this->matchByPatternList[] = $match;
  }

  public function freeze(): MatchSummary {
    // @fixme
    $matchLevel = 0;
    $toolId = '';
    $mayNeedResearch = FALSE;
    return ($this->constructor)($this->matchByDomainList, $this->matchByPatternList, $matchLevel, $toolId, $mayNeedResearch);
  }

}
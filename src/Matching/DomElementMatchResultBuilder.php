<?php

namespace Geeks4change\BbndAnalyzer\Matching;

class DomElementMatchResultBuilder {

  /**
   * @var array<\Geeks4change\BbndAnalyzer\Matching\MatchBase>
   */
  protected array $matchList = [];

  protected \Closure $constructor;

  /**
   * Nothing to see here. Use MatchSummary::builder.
   */
  public function __construct(\Closure $constructor) {
    $this->constructor = $constructor;
  }

  public function addMatch(MatchBase $match) {
    $this->matchList[] = $match;
  }

  public function freeze(): DomElementMatchResult {
    return ($this->constructor)($this->matchList);
  }

}
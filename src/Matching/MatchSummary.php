<?php

namespace Geeks4change\BbndAnalyzer\Matching;

use Geeks4change\BbndAnalyzer\Pattern\ToolPatternCollectionBuilder;

class MatchSummary {

  /**
   * @var array<\Geeks4change\BbndAnalyzer\Matching\MatchByDomain>
   */
  protected array $matchByDomainList;

  /**
   * @var array<\Geeks4change\BbndAnalyzer\Matching\MatchByPattern>
   */
  protected array $matchByPatternList;

  protected string $matchLevel;

  protected string $toolId;

  protected bool $mayNeedResearch;

  /**
   * @param \Geeks4change\BbndAnalyzer\Matching\MatchByDomain[] $matchByDomainList
   * @param \Geeks4change\BbndAnalyzer\Matching\MatchByPattern[] $matchByPatternList
   * @param string $matchLevel
   * @param string $toolId
   * @param bool $mayNeedResearch
   */
  public function __construct(array $matchByDomainList, array $matchByPatternList, string $matchLevel, string $toolId, bool $mayNeedResearch) {
    $this->matchByDomainList = $matchByDomainList;
    $this->matchByPatternList = $matchByPatternList;
    $this->matchLevel = $matchLevel;
    $this->toolId = $toolId;
    $this->mayNeedResearch = $mayNeedResearch;
  }

  /**
   * The only way to construct a MatchSummary.
   */
  public static function builder(): MatchSummaryBuilder {
    return new MatchSummaryBuilder(\Closure::fromCallable(
      fn($matchByDomainList, $matchByPatternList, $matchLevel, $toolId, $mayNeedResearch) =>
      new self($matchByDomainList, $matchByPatternList, $matchLevel, $toolId, $mayNeedResearch)
    ));
  }

}

<?php

namespace Geeks4change\BbndAnalyzer\Matching;

class MatchSummary {

  /**
   * @var array<\Geeks4change\BbndAnalyzer\Matching\MatchBase>
   */
  protected array $matchList;

  protected string $matchLevel;

  protected string $toolId;

  protected bool $mayNeedResearch;

  /**
   * @param \Geeks4change\BbndAnalyzer\Matching\MatchBase[] $matchList
   * @param string $matchLevel
   * @param string $toolId
   * @param bool $mayNeedResearch
   */
  public function __construct(array $matchList, string $matchLevel, string $toolId, bool $mayNeedResearch) {
    $this->matchList = $matchList;
    $this->matchLevel = $matchLevel;
    $this->toolId = $toolId;
    $this->mayNeedResearch = $mayNeedResearch;
  }


  /**
   * The only way to construct a MatchSummary.
   */
  public static function builder(): MatchSummaryBuilder {
    // @fixme Remove obsoleted properties.
    return new MatchSummaryBuilder(\Closure::fromCallable(
      fn($matchList, $matchLevel, $toolId, $mayNeedResearch) =>
      new self($matchList, $matchLevel, $toolId, $mayNeedResearch)
    ));
  }

  /**
   * @return  array<\Geeks4change\BbndAnalyzer\Matching\MatchBase>
   */
  public function getMatchList(): array {
    return $this->matchList;
  }

}

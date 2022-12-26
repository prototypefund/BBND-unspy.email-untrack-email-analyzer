<?php

namespace Geeks4change\BbndAnalyzer\Matching;

class DomElementMatchResult {

  protected string $serviceName;

  /**
   * @var array<\Geeks4change\BbndAnalyzer\Matching\MatchBase>
   */
  protected array $matchList;

  /**
   * @param string $toolId
   * @param \Geeks4change\BbndAnalyzer\Matching\MatchBase[] $matchList
   */
  public function __construct(string $toolId, array $matchList) {
    $this->serviceName = $toolId;
    $this->matchList = $matchList;
  }


  /**
   * The only way to construct a MatchSummary.
   */
  public static function builder(string $serviceName): DomElementMatchResultBuilder {
    return new DomElementMatchResultBuilder(\Closure::fromCallable(
      fn($matchList) => new self($serviceName, $matchList)
    ));
  }

  /**
   * @return string
   */
  public function getServiceName(): string {
    return $this->serviceName;
  }

  /**
   * @return  array<\Geeks4change\BbndAnalyzer\Matching\MatchBase>
   */
  public function getMatchList(): array {
    return $this->matchList;
  }

}

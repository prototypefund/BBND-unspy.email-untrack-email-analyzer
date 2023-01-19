<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\Arrayable\ToArrayInterface;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\Arrayable\ToArrayTrait;

/**
 * ServiceSummary, child of
 *
 * @see \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails
 *
 * @api Will be serialized in persistent storage, any change needs a migration.
 */
final class ResultSummary implements ToArrayInterface {

  use ToArrayTrait;

  protected ?string $serviceName;

  protected ResultSummaryMatchLevel $matchLevel;

  protected ListInfo $listInfo;

  /**
   * @param string|null $serviceName
   * @param \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultSummaryMatchLevel $matchLevel
   * @param \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ListInfo $listInfo
   */
  public function __construct(?string $serviceName, ResultSummaryMatchLevel $matchLevel, ListInfo $listInfo) {
    $this->serviceName = $serviceName;
    $this->matchLevel = $matchLevel;
    $this->listInfo = $listInfo;
  }


  /**
   * @return string|null
   */
  public function getServiceName(): ?string {
    return $this->serviceName;
  }

  /**
   * @return \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultSummaryMatchLevel
   */
  public function getMatchLevel(): ResultSummaryMatchLevel {
    return $this->matchLevel;
  }


  /**
   * @return \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ListInfo
   */
  public function getListInfo(): ListInfo {
    return $this->listInfo;
  }


}

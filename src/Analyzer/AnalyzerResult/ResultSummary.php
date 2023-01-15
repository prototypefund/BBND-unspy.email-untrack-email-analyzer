<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult;

/**
 * ServiceSummary, child of
 * @see \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails
 *
 * @api Will be serialized in persistent storage, any change needs a migration.
 */
final class ResultSummary {

  protected ?string $serviceName;

  /**
   * Match level
   *
   * - sure
   * - quire-sure
   * - unsure
   * - no-match
   *
   * @var string
   */
  protected string $matchLevel;

  protected ListInfo $listInfo;

  /**
   * @param string|null $serviceName
   * @param string $matchLevel
   * @param \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ListInfo $listInfo
   */
  public function __construct(?string $serviceName, string $matchLevel, ListInfo $listInfo) {
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
   * @return string
   */
  public function getMatchLevel(): string {
    return $this->matchLevel;
  }

  /**
   * @return \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ListInfo
   */
  public function getListInfo(): ListInfo {
    return $this->listInfo;
  }


}

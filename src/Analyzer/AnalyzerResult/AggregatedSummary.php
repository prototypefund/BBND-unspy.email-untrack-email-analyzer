<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult;

/**
 * ServiceSummary, child of
 * @see \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultSummaryPart
 *
 * @api Will be serialized in persistent storage, any change needs a migration.
 */
final class AggregatedSummary {

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

  /**
   * @param string|null $serviceName
   * @param string $matchLevel
   */
  public function __construct(?string $serviceName, string $matchLevel) {
    $this->serviceName = $serviceName;
    $this->matchLevel = $matchLevel;
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


}

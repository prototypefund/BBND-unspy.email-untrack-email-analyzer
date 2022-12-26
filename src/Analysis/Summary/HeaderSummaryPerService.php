<?php

declare(strict_types=1);

namespace Geeks4change\BbndAnalyzer\Analysis\Summary;

/**
 * HeaderSummaryPerService, child of
 * @see \Geeks4change\BbndAnalyzer\Analysis\Summary\HeaderSummary
 *
 * @api Will be serialized in persistent storage, any change needs a migration.
 */
final class HeaderSummaryPerService {

  protected string $serviceName;

  /**
   * @var array<\Geeks4change\BbndAnalyzer\Analysis\Summary\HeaderMatchSummary>
   */
  protected array $headerMatchSummaryList;

  /**
   * @param string $serviceName
   * @param \Geeks4change\BbndAnalyzer\Analysis\Summary\HeaderMatchSummary[] $headerMatchSummaryList
   */
  public function __construct(string $serviceName, array $headerMatchSummaryList) {
    $this->serviceName = $serviceName;
    $this->headerMatchSummaryList = $headerMatchSummaryList;
  }

  /**
   * @return string
   */
  public function getServiceName(): string {
    return $this->serviceName;
  }

  /**
   * @return array
   */
  public function getHeaderMatchSummaryList(): array {
    return $this->headerMatchSummaryList;
  }

}

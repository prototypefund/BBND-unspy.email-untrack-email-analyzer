<?php

declare(strict_types=1);

namespace Geeks4change\BbndAnalyzer\Analysis\Summary;

/**
 * @api Will be serialized in persistent storage, any change needs a migration.
 */
final class DomElementPerServiceSummary {

  protected string $serviceName;

  protected int $matchedExactly;

  protected int $matchedByDomain;

  /**
   * @param string $serviceName
   * @param int $matchedExactly
   * @param int $matchedByDomain
   */
  public function __construct(string $serviceName, int $matchedExactly, int $matchedByDomain) {
    $this->serviceName = $serviceName;
    $this->matchedExactly = $matchedExactly;
    $this->matchedByDomain = $matchedByDomain;
  }

  /**
   * @return string
   */
  public function getServiceName(): string {
    return $this->serviceName;
  }

  /**
   * @return int
   */
  public function getMatchedExactly(): int {
    return $this->matchedExactly;
  }

  /**
   * @return int
   */
  public function getMatchedByDomain(): int {
    return $this->matchedByDomain;
  }

}

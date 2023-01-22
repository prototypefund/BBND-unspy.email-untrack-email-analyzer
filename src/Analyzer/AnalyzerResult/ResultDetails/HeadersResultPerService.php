<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\Arrayable\ToArrayInterface;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\Arrayable\ToArrayTrait;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\TestSummary\TestSummaryInterface;

/**
 * HeaderSummaryPerService, child of
 *
 * @see \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\HeadersResult
 *
 * @api Will be serialized in persistent storage, any change needs a migration.
 */
final class HeadersResultPerService implements TestSummaryInterface, ToArrayInterface {

  use ToArrayTrait;

  /**
   * @param string $serviceName
   * @param \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\HeaderSingleResult[] $headerSingleResultList
   */
  public function __construct(
    public readonly string $serviceName,
    public readonly array  $headerSingleResultList
  ) {}

  public function isNonEmpty(): bool {
    return boolval(array_filter($this->headerSingleResultList, fn(HeaderSingleResult $hsm) => $hsm->isMatch()));
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
  public function getHeaderSingleResultList(): array {
    return $this->headerSingleResultList;
  }

  public function getTestSummary(): array {
    $countMatch = count(array_filter($this->headerSingleResultList, fn(HeaderSingleResult $hms) => $hms->isMatch()));
    return $countMatch ? [
      '_countTotal' => count($this->headerSingleResultList),
      '_countMatch' => $countMatch,
    ] : [];
  }

}

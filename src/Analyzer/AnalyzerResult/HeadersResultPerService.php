<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\Arrayable\ToArrayInterface;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\Arrayable\ToArrayTrait;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\TestSummary\TestSummaryInterface;

/**
 * HeaderSummaryPerService, child of
 *
 * @see \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\HeadersResult
 *
 * @api Will be serialized in persistent storage, any change needs a migration.
 */
final class HeadersResultPerService implements TestSummaryInterface, ToArrayInterface {

  use ToArrayTrait;

  protected string $serviceName;

  /**
   * @var array<\Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\HeaderSingleResult>
   */
  protected array $headerSingleResultList;

  /**
   * @param string $serviceName
   * @param \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\HeaderSingleResult[] $headerSingleResults
   */
  public function __construct(string $serviceName, array $headerSingleResults) {
    $this->serviceName = $serviceName;
    $this->headerSingleResultList = $headerSingleResults;
  }

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

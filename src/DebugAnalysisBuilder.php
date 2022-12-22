<?php

declare(strict_types=1);

namespace Geeks4change\BbndAnalyzer;

use Geeks4change\BbndAnalyzer\Matching\MatchByDomain;
use Geeks4change\BbndAnalyzer\Matching\MatchByPattern;
use Geeks4change\BbndAnalyzer\Matching\MatchNone;
use Geeks4change\BbndAnalyzer\Matching\MatchSummary;

final class DebugAnalysisBuilder implements AnalysisBuilderInterface {

  /**
   * @var array<string>
   */
  protected array $auditLines;

  protected string $emailWithHeaders;

  protected MatchSummary $matchSummary;
  public function audit(string $message): void {
    $this->auditLines[] = $message;
  }

  public function setIsValid(): bool {
    return isset($this->emailWithHeaders) && isset($this->matchSummary);
  }

  public function setEmailWithHeaders(string $emailWithHeaders): void {
    $this->emailWithHeaders = $emailWithHeaders;
  }

  public function setMatchSummary(MatchSummary $matchSummary): void {
    $this->matchSummary = $matchSummary;
  }

  public function getAuditLines(): array {
    return $this->auditLines;
  }

  public function getDebugOutput(): string {
    $matchesByClass = [];
    foreach ($this->matchSummary->getMatchList() as $match) {
      $matchesByClass[get_class($match)][] = $match;
    }
    $countsByClass = array_map(
      fn(string $class) => count($matchesByClass[$class] ?? []),
      [MatchByPattern::class, MatchByDomain::class, MatchNone::class]
    );
    return implode('/', $countsByClass);
  }

}

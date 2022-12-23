<?php

declare(strict_types=1);

namespace Geeks4change\BbndAnalyzer;

use Geeks4change\BbndAnalyzer\DomElement\Image;
use Geeks4change\BbndAnalyzer\DomElement\Link;
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

  public function getDebugOutput(): array {
    $matchMap = [
      MatchByPattern::class => 'pattern',
      MatchByDomain::class => 'domain',
      MatchNone::class => 'none',
    ];
    $domElementMap = [
      Link::class => 'link',
      Image::class => 'image',
    ];
    $matchesByClass = array_fill_keys($matchMap, []);
    foreach ($this->matchSummary->getMatchList() as $match) {
      $group = $matchMap[get_class($match)];
      $url = $match->getDomElement()
        ->getUrl()
        ->getOriginalUrl();
      $type = $domElementMap[get_class($match->getDomElement())];
      $matchesByClass[$group][] = "$type: $url";
    }
    return $matchesByClass;
  }

}

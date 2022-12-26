<?php

declare(strict_types=1);

namespace Geeks4change\BbndAnalyzer\Analysis\SummaryExtractor;

use Geeks4change\BbndAnalyzer\Analysis\Summary\DomElementSummary;
use Geeks4change\BbndAnalyzer\Matching\DomElementMatchResult;
use Geeks4change\BbndAnalyzer\Matching\MatchByDomain;
use Geeks4change\BbndAnalyzer\Matching\MatchByPattern;
use Geeks4change\BbndAnalyzer\Matching\MatchNone;

final class DomElementSummaryExtractor {

  public function extractSummary(DomElementMatchResult $domElementMatchResult, string $domElementClass): DomElementSummary {
    $matchedExactly = $matchedByDomain = $matchedNone = 0;
    foreach ($domElementMatchResult->getMatchList() as $match) {
      if ($match->getDomElement() instanceof $domElementClass) {
        // @todo Rename MatchByPattern to MatchExactly.
        if ($match instanceof MatchByPattern) {
          $matchedExactly ++;
        }
        if ($match instanceof MatchByDomain) {
          $matchedByDomain ++;
        }
        if ($match instanceof MatchNone) {
          $matchedNone ++;
        }
      }
    }
    return new DomElementSummary(
      $matchedExactly,
      $matchedByDomain,
      $matchedNone,
      $domElementMatchResult->getServiceName(),
      FALSE
    );
  }

}

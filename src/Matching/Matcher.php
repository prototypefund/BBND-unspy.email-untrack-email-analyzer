<?php

namespace Geeks4change\BbndAnalyzer\Matching;

use Geeks4change\BbndAnalyzer\DomElement\DomElementCollection;
use Geeks4change\BbndAnalyzer\DomElement\Image;
use Geeks4change\BbndAnalyzer\DomElement\Link;
use Geeks4change\BbndAnalyzer\Pattern\ToolPatternRepository;

class Matcher {

  public function match(DomElementCollection $domElementCollection): MatchSummary {
    $patternRepository = new ToolPatternRepository();

    $matchSummaryBuilder = MatchSummary::builder();
    /** @var \Geeks4change\BbndAnalyzer\DomElement\DomElementInterface $domElement */
    foreach ($domElementCollection as $domElement) {

      // @todo Only match domain when no link match.
      // @todo Add a "none" match when no match.
      /** @var \Geeks4change\BbndAnalyzer\Pattern\ToolPattern $toolPattern */
      foreach ($patternRepository->getToolPatternCollection() as $toolPattern) {
        // We check every domElement against every toolPattern.
        // Every pairing just gets one match recorded, either
        // - a specific PatternMatch
        // - an unspecific DomainMatch
        // - NoMatch
        // Note that if one domElement can be matched by more than one
        // toolPattern, but if it is, then one of them must be broken.
        $currentMatch = NULL;

        // Try matching a specific PatternMatch
        if ($domElement instanceof Link) {
          /** @var \Geeks4change\BbndAnalyzer\Pattern\UrlPatternForLink $linkPattern */
          foreach ($toolPattern->getLinkPatterns() as $linkPattern) {
            if ($linkPattern->matches($domElement)) {
              $currentMatch = new MatchByPattern($domElement, $linkPattern);
            }
          }
        }
        if ($domElement instanceof Image) {
          /** @var \Geeks4change\BbndAnalyzer\Pattern\UrlPatternForImage $imagePattern */
          foreach ($toolPattern->getImagePatterns() as $imagePattern) {
            if ($imagePattern->matches($domElement)) {
              $currentMatch = new MatchByPattern($domElement, $imagePattern);
            }
          }
        }

        // If none, try matching an unspecific DomainMatch.
        if (!$currentMatch) {
          foreach ($toolPattern->getDomainPatterns() as $domainPattern) {
            if ($domainPattern->matches($domElement->getUrl()->getHost())) {
              $currentMatch = new MatchByDomain($domElement, $domainPattern);
            }
          }
        }

        // Otherwise record MatchNone
        if (!$currentMatch) {
          $currentMatch = new MatchNone($domElement);
        }
        $matchSummaryBuilder->addMatch($currentMatch);

      }
    }
    return $matchSummaryBuilder->freeze();
  }

}
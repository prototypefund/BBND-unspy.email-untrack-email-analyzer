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
        foreach ($toolPattern->getDomainPatterns() as $domainPattern) {
          if ($domainPattern->matches($domElement->getUrl()->getHost())) {
            $matchSummaryBuilder->addMatchByDomain(new MatchByDomain($domElement, $domainPattern));
          }
        }

        if ($domElement instanceof Link) {
          /** @var \Geeks4change\BbndAnalyzer\Pattern\UrlPatternForLink $linkPattern */
          foreach ($toolPattern->getLinkPatterns() as $linkPattern) {
            if ($linkPattern->matches($domElement)) {
              $matchSummaryBuilder->addMatchByPattern(new MatchByPattern($domElement, $linkPattern));
            }
          }
        }
        if ($domElement instanceof Image) {
          /** @var \Geeks4change\BbndAnalyzer\Pattern\UrlPatternForImage $imagePattern */
          foreach ($toolPattern->getImagePatterns() as $imagePattern) {
            if ($imagePattern->matches($domElement)) {
              $matchSummaryBuilder->addMatchByPattern(new MatchByPattern($domElement, $imagePattern));
            }
          }
        }

      }
    }
    return $matchSummaryBuilder->freeze();
  }

}
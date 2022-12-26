<?php

declare(strict_types=1);

namespace Geeks4change\BbndAnalyzer\Matching;

use Geeks4change\BbndAnalyzer\DomElement\DomElementCollection;
use Geeks4change\BbndAnalyzer\DomElement\Image;
use Geeks4change\BbndAnalyzer\DomElement\Link;
use Geeks4change\BbndAnalyzer\Pattern\ToolPatternRepository;
use ZBateson\MailMimeParser\Message;

final class Matcher {

  protected ToolPatternRepository $toolPatternRepository;

  public function __construct() {
    $this->toolPatternRepository = new ToolPatternRepository();
  }


  public function matchDomElements(DomElementCollection $domElementCollection): DomElementMatchResult {
    $matchSummaryBuilder = DomElementMatchResult::builder();
    /** @var \Geeks4change\BbndAnalyzer\DomElement\DomElementInterface $domElement */
    foreach ($domElementCollection as $domElement) {
      $domElementHasMatch = FALSE;

      /** @var \Geeks4change\BbndAnalyzer\Pattern\ToolPattern $toolPattern */
      foreach ($this->toolPatternRepository->getToolPatternCollection() as $toolPattern) {
        // We check every domElement against every toolPattern.
        // Every pairing just gets one match recorded, either
        // - a specific PatternMatch
        // - an unspecific DomainMatch
        // - NoMatch
        // Note that if one domElement can be matched by more than one
        // toolPattern, but if it is, then one of them must be broken.
        $domElementToolPatternMatch = NULL;

        // Try matching a specific PatternMatch
        if ($domElement instanceof Link) {
          /** @var \Geeks4change\BbndAnalyzer\Pattern\UrlPatternForLink $linkPattern */
          foreach ($toolPattern->getLinkPatterns() as $linkPattern) {
            if ($linkPattern->matches($domElement)) {
              $domElementToolPatternMatch = new MatchByPattern($domElement, $linkPattern);
            }
          }
        }
        elseif ($domElement instanceof Image) {
          /** @var \Geeks4change\BbndAnalyzer\Pattern\UrlPatternForImage $imagePattern */
          foreach ($toolPattern->getImagePatterns() as $imagePattern) {
            if ($imagePattern->matches($domElement)) {
              $domElementToolPatternMatch = new MatchByPattern($domElement, $imagePattern);
            }
          }
        }

        // If none, try matching an unspecific DomainMatch.
        if (!$domElementToolPatternMatch) {
          foreach ($toolPattern->getDomainPatterns() as $domainPattern) {
            if ($domainPattern->matches($domElement->getUrl()->getEffectiveHosts())) {
              $domElementToolPatternMatch = new MatchByDomain($domElement, $domainPattern);
            }
          }
        }

        // Otherwise record MatchNone
        if ($domElementToolPatternMatch) {
          $domElementHasMatch = TRUE;
          $matchSummaryBuilder->addMatch($domElementToolPatternMatch);
        }

      }
      if (!$domElementHasMatch) {
        $matchSummaryBuilder->addMatch(new MatchNone($domElement));
      }
    }
    return $matchSummaryBuilder->freeze();
  }

}
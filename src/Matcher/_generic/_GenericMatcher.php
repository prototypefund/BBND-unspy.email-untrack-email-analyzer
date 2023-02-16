<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Matcher\_generic;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\Result\Header\HeaderItem;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\Result\Header\HeaderItemMatch;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\Result\Url\LinkUrl;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\Result\Url\Match\ProviderMatch;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\Result\Url\UrlItem;
use Geeks4change\UntrackEmailAnalyzer\Matcher\MatcherInterface;

final class _genericMatcher implements MatcherInterface {

  public function getId(): string {
    return '_generic';
  }

  public function matchHeader(HeaderItem $item): ?HeaderItemMatch {
    return NULL;
  }

  public function matchUrl(UrlItem $urlItem): ?ProviderMatch {
    if ($urlItem instanceof LinkUrl) {
      $match = preg_match('~unsubscribe|abmelden|austragen~ui', $urlItem->text);
      dump(get_defined_vars());
      if ($match) {
        return new ProviderMatch('generic', 'looks like unsubscribe', false, true);
      }
    }
    return NULL;
  }

}

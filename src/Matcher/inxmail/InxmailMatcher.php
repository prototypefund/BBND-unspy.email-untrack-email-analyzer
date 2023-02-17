<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Matcher\inxmail;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\Result\Header\HeaderItem;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\Result\Header\HeaderItemMatch;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\Result\Url\Match\ProviderMatch;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\Result\Url\UrlItem;
use Geeks4change\UntrackEmailAnalyzer\Matcher\MatcherInterface;
use Geeks4change\UntrackEmailAnalyzer\Utility\Extract;
use Geeks4change\UntrackEmailAnalyzer\Utility\UrlMatcher;

final class InxmailMatcher implements MatcherInterface {

  public function getId(): string {
    $class = get_class($this);
    $parts = explode('\\', $class);
    $reverseParts = array_reverse($parts);
    return $reverseParts[1];
  }

  public function matchHeader(HeaderItem $item): ?HeaderItemMatch {
    // X-Mailer: Inxmail EE 4.8.45.741
    if ($item->name === 'x-mailer') {
      $isMatch = str_starts_with($item->value, 'Inxmail ');
    }
    // Feedback-ID: xpro-7184-34660:xpro-7184:MAILING:inxmailde
    elseif ($item->name === 'feedback-id') {
      $isMatch = str_ends_with($item->value, ':inxmailde');
    }
    // List-Unsubscribe: <https://news.phineo.org/d?o000khqq00clmm00l0000lyi000000000efkxqvyhtzxyxq2pm4x2dg06du402>, <mailto:news_phineo@inxserver.com?subject=unsubscribe%20params%3Db0000deq00clmm000q5s00000000000bbkxbmqme7>
    elseif ($item->name === 'list-unsubscribe') {
      $parts = Extract::angleBrackets($item->value);
      $isMatch = $parts && UrlMatcher::create('//rdir.inxmail.com/d')->match($parts[0]);
    }
    return isset($isMatch) ? new HeaderItemMatch($this->getId(), $isMatch) : NULL;
  }

  public function matchUrl(UrlItem $urlItem): ?ProviderMatch {
    return NULL;
  }

}

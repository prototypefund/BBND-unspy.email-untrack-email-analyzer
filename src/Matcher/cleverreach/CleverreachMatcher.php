<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Matcher\cleverreach;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\Result\Header\HeaderItem;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\Result\Header\HeaderItemMatch;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\Result\Url\Match\ProviderMatch;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\Result\Url\UrlItem;
use Geeks4change\UntrackEmailAnalyzer\Matcher\MatcherIdTrait;
use Geeks4change\UntrackEmailAnalyzer\Matcher\MatcherInterface;
use Geeks4change\UntrackEmailAnalyzer\Utility\Extract;
use Geeks4change\UntrackEmailAnalyzer\Utility\UrlMatcher;

final class CleverreachMatcher implements MatcherInterface {

  use MatcherIdTrait;

  public function matchHeader(HeaderItem $item): ?HeaderItemMatch {
    # Message-ID: <20230110092804.121070.14141611.1284@bounce-eu2.crsend.com>
    if ($item->name === 'message-id') {
      $isMatch = UrlMatcher::create('.crsend.com')->match($item->value);
    }
    # List-Unsubscribe: <https://121070.seu2.cleverreach.com/rmftlp.php?cid=0&mid=14141611&h=0-b09ec13a504>
    elseif ($item->name === 'list-unsubscribe') {
      $inAngleBrackets = Extract::angleBrackets($item->value);
      $isMatch = $inAngleBrackets && UrlMatcher::create('.cleverreach.com')
          ->match($inAngleBrackets[0]);
    }
    #
    elseif ($item->name === 'list-id') {
      $inAngleBrackets = Extract::angleBrackets($item->value);
      $isMatch = $inAngleBrackets && UrlMatcher::create('.crsend.com')
          ->match($inAngleBrackets[0]);
    }
    elseif ($item->name === 'feedback-id') {
      $isMatch = str_ends_with($item->value, ':121070');
    }
    return isset($isMatch) ? new HeaderItemMatch($this->getId(), $isMatch) : NULL;
  }

  public function matchUrl(UrlItem $urlItem): ?ProviderMatch {
    // User tracking.
    # https://121070.seu2.cleverreach.com/m/14198018/0-9331dfce514dd160c9d6775bec150845148de6c1f9241580b8b1b622e445ee24e89940ee265bac2318515cfbed19dfd3
    # https://121070.seu2.cleverreach.com/m/14198018/0-2d5ba8bae311b55d6d62702a9e09dc1d33b4944d55655a74a810ce9ddc5e3f57ce55ba79bcddc4db0508257f1f62c335
    if (UrlMatcher::create('.cleverreach.com/m/{}/{}')->match($urlItem->url)) {
      return new ProviderMatch($this->getId(), 'webview', TRUE, FALSE);
    }
    # https://121070.seu2.cleverreach.com/c/79919679/b09ec13a504-rpen8x
    # https://121070.seu2.cleverreach.com/c/79919679/bd26b37c503-rpen8x
    elseif (UrlMatcher::create('.cleverreach.com/c/{}/{}')->match($urlItem->url)) {
      return new ProviderMatch($this->getId(), 'spy-link', TRUE, FALSE);
    }

    // No technical urls, they are just user tracking urls.
    // No one-click unsubscribe.

    // Images.
    # https://files.crsend.com/121000/121070/images/Newsletter_Grafik_150dpi.png
    if (UrlMatcher::create('files.crsend.com')->match($urlItem->url)) {
      return new ProviderMatch($this->getId(), 'referral', FALSE, FALSE);
    }
    # https://stats-eu2.crsend.com/stats/mc_121070_14141611_b09ec13a504-ro9gus.gif
    if (UrlMatcher::create('.crsend.com/stats/{}')->match($urlItem->url)) {
      return new ProviderMatch($this->getId(), 'user tracking pixel', TRUE, FALSE);
    }

    // Domain.
    if (UrlMatcher::create('', $this->getDomains())->match($urlItem->url)) {
      return new ProviderMatch($this->getId(), 'by domain', FALSE, FALSE);
    }

    return NULL;
  }

  protected function getDomains(): array {
    return [
      '.cleverreach.com',
      '.crsend.com',
    ];
  }

}

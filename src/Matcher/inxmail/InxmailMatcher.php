<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Matcher\inxmail;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\Result\Header\HeaderItem;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\Result\Header\HeaderItemMatch;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\Result\Url\Match\ProviderMatch;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\Result\Url\UrlItem;
use Geeks4change\UntrackEmailAnalyzer\Matcher\MatcherIdTrait;
use Geeks4change\UntrackEmailAnalyzer\Matcher\MatcherInterface;
use Geeks4change\UntrackEmailAnalyzer\Utility\Extract;
use Geeks4change\UntrackEmailAnalyzer\Utility\UrlMatcher;

final class InxmailMatcher implements MatcherInterface {

  use MatcherIdTrait;

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
      $isMatch = $parts && UrlMatcher::create('rdir.inxmail.com/d')->match($parts[0]);
    }
    return isset($isMatch) ? new HeaderItemMatch($this->getId(), $isMatch) : NULL;
  }

  public function matchUrl(UrlItem $urlItem): ?ProviderMatch {
    // User tracking.
    // Webview seems same.
    # https://news.phineo.org/d?o000khlq00clmmbid0000lyi000000000efkxqvyhtzxyxq2pm4x2dghehi402
    // @todo A query KEY pattern might be handsome here.
    if (UrlMatcher::create('rdir.inxmail.com/d')->match($urlItem->url)) {
      return new ProviderMatch($this->getId(), 'webview', true, false);
    }

    // Technical.
    # https://media.inx-cdn.de/4a2d96f7-ddf8-4826-8553-24e968a97940/b0473e81-3693-4dc8-b6d9-a31f3e21ba9c.jpg
    if (UrlMatcher::create('media.inx-cdn.de')->match($urlItem->url)) {
      return new ProviderMatch($this->getId(), 'user content', false, false);
    }

    // Images.
    # https://ts-svc.inxserver.com/transparent.gif
    if (UrlMatcher::create('ts-svc.inxserver.com/transparent.gif')->match($urlItem->url)) {
      return new ProviderMatch($this->getId(), 'pixel used in tables', false, false);
    }
    # https://news.phineo.org/d/d.gif?o000khpq00clmm00h0000lyi000000000efkxqvyhtzxyxq2pm4x2dge4si402
    // @todo A query KEY pattern might be handsome here.
    if (UrlMatcher::create('rdir.inxmail.com/d/d.gif')->match($urlItem->url)) {
      return new ProviderMatch($this->getId(), 'opener tracking pixel', true, false);
    }

    // Domain.
    if (UrlMatcher::create('', $this->getDomains())->match($urlItem->url)) {
      return new ProviderMatch($this->getId(), 'by domain', false, false);
    }

    return NULL;
  }

  protected function getDomains(): array {
    return [
      '.inxmail.com',
      '.inxmail.de',
      '.inserver.com',
      '.inx-cdn.de',
    ];
  }

}

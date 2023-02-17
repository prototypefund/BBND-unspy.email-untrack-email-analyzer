<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Matcher\mailchimp;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\Result\Header\HeaderItem;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\Result\Header\HeaderItemMatch;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\Result\Url\Match\ProviderMatch;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\Result\Url\UrlItem;
use Geeks4change\UntrackEmailAnalyzer\Matcher\MatcherIdTrait;
use Geeks4change\UntrackEmailAnalyzer\Matcher\MatcherInterface;
use Geeks4change\UntrackEmailAnalyzer\Utility\Extract;
use Geeks4change\UntrackEmailAnalyzer\Utility\UrlMatcher;

final class MailchimpMatcher implements MatcherInterface {

  use MatcherIdTrait;

  public function matchHeader(HeaderItem $item): ?HeaderItemMatch {
    // Message-ID: <b00ccdbb39a8456492b99ae9e.be24ad69fc.20220626160230.a8d0ad03b7.fd6563fe@mail16.sea31.mcsv.net>
    // Message-ID: '<{sender}.{recipient}.{_somedate:dec(14)}.{campaign}.{:hex(8)}@{=mail16}.{=sea31}.mcsv.net>'
    // But may be different domains, so check for any.
    if ($item->name === 'message-id') {
      $isMatch = UrlMatcher::create('', $this->getDomains())->match($item->value);
    }
    // List-Unsubscribe: <https://voeoe.us1.list-manage.com/unsubscribe?u=b00ccdbb39a8456492b99ae9e&id=ede4b53575&e=be24ad69fc&c=a8d0ad03b7>, <mailto:unsubscribe-mc.{region}_b00ccdbb39a8456492b99ae9e.a8d0ad03b7-be24ad69fc@unsubscribe.mailchimpapp.net?subject=unsubscribe>
    // List-Unsubscribe: '<https://{sender-slug}.{region}.list-manage.com/unsubscribe?u={sender}&id={subscription}&e={recipient}&c={campaign}>, <mailto:unsubscribe-mc.{region}_{sender}.{campaign}-{recipient}@unsubscribe.mailchimpapp.net?subject=unsubscribe>'
    elseif ($item->name === 'list-unsubscribe') {
      $inAngleBrackets = Extract::angleBrackets($item->value);
      $isMatch = $inAngleBrackets && UrlMatcher::create('.list-manage.com/unsubscribe', $this->getDomains())
        ->match($inAngleBrackets[0]);
    }
    // List-ID: b00ccdbb39a8456492b99ae9emc list <b00ccdbb39a8456492b99ae9e.1621274.list-id.mcsv.net>
    // List-ID: '{sender}mc list <{sender}.{?1621274}.list-id.mcsv.net>'
    elseif ($item->name === 'list-id') {
      $inAngleBrackets = Extract::angleBrackets($item->value);
      $isMatch = $inAngleBrackets && UrlMatcher::create('', $this->getDomains())
          ->match($inAngleBrackets[0]);
    }
    elseif ($item->name === 'x-mailer') {
      $isMatch = str_starts_with($item->value, 'Mailchimp Mailer ');
    }
    elseif ($item->name === 'x-campaign' || $item->name === 'x-campaignid') {
      $isMatch = str_starts_with($item->value, 'mailchimp');
    }
    elseif ($item->name === 'x-report-abuse') {
      $isMatch = str_contains($item->value, 'https://mailchimp.com/contact/abuse');
    }
    elseif ($item->name === 'x-mc-user') {
      $isMatch = TRUE;
    }
    elseif ($item->name === 'feedback-id') {
      $isMatch = str_ends_with($item->value, ':mc');
    }
    return isset($isMatch) ? new HeaderItemMatch($this->getId(), $isMatch) : NULL;
  }

  public function matchUrl(UrlItem $urlItem): ?ProviderMatch {
    // Guessing parameters:
    // u: sender
    // id: list
    // e: recipient
    // c: campaign

    // User tracking.
    # https://mailchi.mp/f84d35f613cc/voeoe-03-8987958?e=be24ad69fc
    if (UrlMatcher::create('mailchi.mp/{}/{}?e=')->match($urlItem->url)) {
      return new ProviderMatch($this->getId(), 'webview', true, false);
    }
    # https://voeoe.us1.list-manage.com/track/click?u=b00ccdbb39a8456492b99ae9e&id=e269fce298&e=be24ad69fc
    // The 'e' query is the user tracking part.
    if (UrlMatcher::create('.list-manage.com/track/click?e=')->match($urlItem->url)) {
      return new ProviderMatch($this->getId(), 'spy-link', true, false);
    }

    // Technical.
    # https://voeoe.us1.list-manage.com/unsubscribe?u=b00ccdbb39a8456492b99ae9e&id=ede4b53575&e=be24ad69fc&c=a8d0ad03b7
    if (UrlMatcher::create('.list-manage.com/unsubscribe')->match($urlItem->url)) {
      return new ProviderMatch($this->getId(), 'unsubscribe', false, true);
    }
    # https://voeoe.us1.list-manage.com/vcard?u=b00ccdbb39a8456492b99ae9e&id=ede4b53575
    if (UrlMatcher::create('.list-manage.com/vcard')->match($urlItem->url)) {
      return new ProviderMatch($this->getId(), 'vcard', false, true);
    }
    # https://voeoe.us1.list-manage.com/profile?u=b00ccdbb39a8456492b99ae9e&id=ede4b53575&e=be24ad69fc&c=a8d0ad03b7
    if (UrlMatcher::create('.list-manage.com/profile')->match($urlItem->url)) {
      return new ProviderMatch($this->getId(), 'profile', false, true);
    }
    # http://www.mailchimp.com/email-referral/?utm_source=freemium_newsletter&utm_medium=email&utm_campaign=referral_marketing&aid=b00ccdbb39a8456492b99ae9e&afl=1
    if (UrlMatcher::create('.mailchimp.com/email-referral')->match($urlItem->url)) {
      return new ProviderMatch($this->getId(), 'referral', false, true);
    }

    // Images.
    # Verified that different recipients get the same URL, so no user tracking.
    # https://mcusercontent.com/b00ccdbb39a8456492b99ae9e/images/e855e0cb-25e8-47a9-8f3f-eb89de3776b6.png
    if (UrlMatcher::create('mcusercontent.com/{sender}/images/{}.{image_extension}')->match($urlItem->url)) {
      return new ProviderMatch($this->getId(), 'referral', false, false);
    }
    # https://cdn-images.mailchimp.com/icons/social-block-v2/color-twitter-48.png
    if (UrlMatcher::create('cdn-images.mailchimp.com')->match($urlItem->url)) {
      return new ProviderMatch($this->getId(), 'technical image', false, false);
    }
    # https://voeoe.us1.list-manage.com/track/open.php?u=b00ccdbb39a8456492b99ae9e&id=a8d0ad03b7&e=be24ad69fc
    // The 'e' query is the user tracking part.
    if (UrlMatcher::create('.list-manage.com/track/open.php?e=')->match($urlItem->url)) {
      return new ProviderMatch($this->getId(), 'tracking pixel', true, false);
    }

    // Domain.
    if (UrlMatcher::create('', $this->getDomains())->match($urlItem->url)) {
      return new ProviderMatch($this->getId(), 'by domain', false, false);
    }

    return NULL;
  }

  protected function getDomains(): array {
    return [
      'mailchimp.com',
      'mailchimpapp.net',
      'mailchi.mp',
      '.list-manage.com',
      '.list-manage.com.edgekey.net',
      'mcusercontent.com',
      'agentofficemail.com',
      'answerbook.com',
      'campaign-archive1.com',
      'list-manage1.com',
      'mandrillapp.com',
      'tinyletter.com',
      '.mcsv.net',
      '.mcdlv.net',
    ];
  }

}

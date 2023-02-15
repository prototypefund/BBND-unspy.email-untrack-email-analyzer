<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Matcher2\Mailchimp;

use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Header\HeaderItem;
use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\Match\ProviderMatch;
use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\UrlItem;
use Geeks4change\UntrackEmailAnalyzer\Matcher2\MatcherBase;
use Geeks4change\UntrackEmailAnalyzer\Matcher2\MatcherInterface;
use Geeks4change\UntrackEmailAnalyzer\Utility\UrlMatcher;

final class MailchimpMatcher extends MatcherBase implements MatcherInterface {

  protected function getDomains(): array {
    return [
      'mailchimp.com',
      'mailchimpapp.net',
      'mailchi.mp',
      'list-manage.com',
      'list-manage.com.edgekey.net',
      'mcusercontent.com',
      'agentofficemail.com',
      'answerbook.com',
      'campaign-archive1.com',
      'list-manage1.com',
      'mandrillapp.com',
      'tinyletter.com',
      'mcsv.net',
      'mcdlv.net',
    ];
  }

  public function getId(): string {
    return 'mailchimp';
  }

  public function matchHeader(HeaderItem $item): ?bool {
    if ($item->name === 'message-id') {
      return $this->stringMatchesDomain($item->value);
    }
    elseif ($item->name === 'list-unsubscribe') {
      return $this->anyHostInAngleBracketsMatchesAnyDomain($item->value);
    }
    elseif ($item->name === 'list-id') {
      return $this->anyValueInAngleBracketsMatchesAnyDomain($item->value);
    }
    elseif ($item->name === 'x-mailer') {
      return str_starts_with($item->value, 'Mailchimp Mailer ');
    }
    elseif ($item->name === 'x-campaign' || $item->name === 'x-campaignid') {
      return str_starts_with($item->value, 'mailchimp');
    }
    elseif ($item->name === 'x-report-abuse') {
      return str_contains($item->value, 'https://mailchimp.com/contact/abuse');
    }
    elseif ($item->name === 'x-mc-user') {
      return TRUE;
    }
    elseif ($item->name === 'feedback-id') {
      return str_ends_with($item->value, ':mc');
    }
    return NULL;
  }

  public function matchTechnicalUrl(UrlItem $urlItem): bool {
    // Guessing parameters:
    // u: sender
    // id: list
    // e: recipient
    // c: campaign
    # https://voeoe.us1.list-manage.com/unsubscribe?u=b00ccdbb39a8456492b99ae9e&id=ede4b53575&e=be24ad69fc&c=a8d0ad03b7
    $isUnsubscribe = UrlMatcher::create('//{}.list-manage.com/unsubscribe')->match($urlItem->url, );
    # https://voeoe.us1.list-manage.com/vcard?u=b00ccdbb39a8456492b99ae9e&id=ede4b53575
    $isVcard = UrlMatcher::create('//{}.list-manage.com/vcard')->match($urlItem->url, );
    # https://voeoe.us1.list-manage.com/profile?u=b00ccdbb39a8456492b99ae9e&id=ede4b53575&e=be24ad69fc&c=a8d0ad03b7
    $isProfile = UrlMatcher::create('//{}.list-manage.com/vcard')->match($urlItem->url, );
    # http://www.mailchimp.com/email-referral/?utm_source=freemium_newsletter&utm_medium=email&utm_campaign=referral_marketing&aid=b00ccdbb39a8456492b99ae9e&afl=1
    $isReferral = UrlMatcher::create('//{}.mailchimp.com/email-referral')->match($urlItem->url, );
    return $isUnsubscribe || $isVcard || $isProfile || $isReferral;
  }

  public function matchUserTrackingUrl(UrlItem $urlItem): bool {
    # https://mailchi.mp/f84d35f613cc/voeoe-03-8987958?e=be24ad69fc
    $isWebview = UrlMatcher::create('//mailchi.mp/{}/{}?e={}')->match($urlItem->url);
    # https://voeoe.us1.list-manage.com/track/click?u=b00ccdbb39a8456492b99ae9e&id=e269fce298&e=be24ad69fc
    $isUserTracking = UrlMatcher::create('//list-manage.com/track/click?e=')->match($urlItem->url);
    return $isWebview || $isUserTracking;
  }

  public function matchUrl(UrlItem $urlItem): ?ProviderMatch {
    // Guessing parameters:
    // u: sender
    // id: list
    // e: recipient
    // c: campaign

    // User tracking.
    # https://mailchi.mp/f84d35f613cc/voeoe-03-8987958?e=be24ad69fc
    if (UrlMatcher::create('//mailchi.mp/{}/{}?e={}')->match($urlItem->url)) {
      return new ProviderMatch($this->getId(), 'webview', true, false);
    }
    # https://voeoe.us1.list-manage.com/track/click?u=b00ccdbb39a8456492b99ae9e&id=e269fce298&e=be24ad69fc
    if (UrlMatcher::create('//list-manage.com/track/click?e=')->match($urlItem->url)) {
      return new ProviderMatch($this->getId(), 'spy-link', true, false);
    }

    // Technical.
    # https://voeoe.us1.list-manage.com/unsubscribe?u=b00ccdbb39a8456492b99ae9e&id=ede4b53575&e=be24ad69fc&c=a8d0ad03b7
    if (UrlMatcher::create('//{}.list-manage.com/unsubscribe')->match($urlItem->url, )) {
      return new ProviderMatch($this->getId(), 'unsubscribe', false, true);
    }
    # https://voeoe.us1.list-manage.com/vcard?u=b00ccdbb39a8456492b99ae9e&id=ede4b53575
    if (UrlMatcher::create('//{}.list-manage.com/vcard')->match($urlItem->url, )) {
      return new ProviderMatch($this->getId(), 'vcard', false, true);
    }
    # https://voeoe.us1.list-manage.com/profile?u=b00ccdbb39a8456492b99ae9e&id=ede4b53575&e=be24ad69fc&c=a8d0ad03b7
    if (UrlMatcher::create('//{}.list-manage.com/profile')->match($urlItem->url, )) {
      return new ProviderMatch($this->getId(), 'profile', false, true);
    }
    # http://www.mailchimp.com/email-referral/?utm_source=freemium_newsletter&utm_medium=email&utm_campaign=referral_marketing&aid=b00ccdbb39a8456492b99ae9e&afl=1
    if (UrlMatcher::create('//{}.mailchimp.com/email-referral')->match($urlItem->url, )) {
      return new ProviderMatch($this->getId(), 'referral', false, true);
    }

    // Domain.
    if ($this->urlMatchesDomain($urlItem)) {
      return new ProviderMatch($this->getId(), 'referral', false, false);
    }

    return NULL;
  }

}

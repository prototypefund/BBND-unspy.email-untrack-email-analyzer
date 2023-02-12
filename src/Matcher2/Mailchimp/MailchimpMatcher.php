<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Matcher2\Mailchimp;

use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Header\HeaderItem;
use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\UrlItem;
use Geeks4change\UntrackEmailAnalyzer\Matcher2\MatcherTrait;
use Geeks4change\UntrackEmailAnalyzer\Matcher2\MatcherInterface;

final class MailchimpMatcher implements MatcherInterface {

  use MatcherTrait;

  public function getDomains(): array {
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

  public function matchHeader(HeaderItem $item): bool {
    if ($item->name === 'message-id') {
      return $this->matchesAnyDomain($item->value);
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
    return FALSE;
  }

  public function matchUnsubscribeUrl(UrlItem $urlItem): bool {
    return $this->matchUrl($urlItem, 'unsubscribe');
  }

  public function matchUserTrackingUrl(UrlItem $urlItem): bool {
    // @fixme
    return FALSE;
  }

  public function matchDomainUrl(UrlItem $urlItem): bool {
    return $this->matchesAnyDomain($this->extractHost($urlItem->url));
  }

}

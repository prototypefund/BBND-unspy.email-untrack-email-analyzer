<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Matcher2\Mailchimp;

use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Header\HeaderItemInfoBagBuilder;
use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Header\HeaderItemMatch;
use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\UrlItem;
use Geeks4change\UntrackEmailAnalyzer\Matcher2\MatcherTrait;
use Geeks4change\UntrackEmailAnalyzer\Matcher2\Matcher;
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

  public function createHeaderItemMatch(): HeaderItemMatch {
    return new HeaderItemMatch($this->getId());
  }

  public function matchHeaders(HeaderItemInfoBagBuilder $builder): void {
    foreach ($builder->getHeaderItems() as $item) {
      if ($item->name === 'message-id') {
        if ($this->matchesAnyDomain($item->value)) {
          $builder->addMatch($item, $this->createHeaderItemMatch());
        }
      }
      elseif ($item->name === 'list-unsubscribe') {
        $match = $this->anyHostInAngleBracketsMatchesAnyDomain($item->value);
        if ($match) {
          $builder->addMatch($item, $this->createHeaderItemMatch());
        }
      }
      elseif ($item->name === 'list-id') {
        if ($this->anyValueInAngleBracketsMatchesAnyDomain($item->value)) {
          $builder->addMatch($item, $this->createHeaderItemMatch());
        }
      }
      elseif ($item->name === 'x-mailer') {
        if (str_starts_with($item->value, 'Mailchimp Mailer ')) {
          $builder->addMatch($item, $this->createHeaderItemMatch());
        }
      }
      elseif ($item->name === 'x-campaign' || $item->name === 'x-campaignid') {
        if (str_starts_with($item->value, 'mailchimp')) {
          $builder->addMatch($item, $this->createHeaderItemMatch());
        }
      }
      elseif ($item->name === 'x-report-abuse') {
        if (str_contains($item->value, 'https://mailchimp.com/contact/abuse')) {
          $builder->addMatch($item, $this->createHeaderItemMatch());
        }
      }
      elseif ($item->name === 'x-mc-user') {
        $builder->addMatch($item, $this->createHeaderItemMatch());
      }
      elseif ($item->name === 'feedback-id') {
        if (str_ends_with($item->value, ':mc')) {
          $builder->addMatch($item, $this->createHeaderItemMatch());
        }
      }
    }
  }

  public function matchUnsubscribeUrl(UrlItem $urlItem): bool {
    return $this->matchUrl($urlItem, 'unsubscribe');
  }

}

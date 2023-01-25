<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\NewsletterServicesMatcher\HeadersMatcher;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\HeaderMatch;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\HeaderMatchList;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\HeaderMatchListPerProvider;
use Geeks4change\UntrackEmailAnalyzer\Globals;
use ZBateson\MailMimeParser\Message;

final class AllServicesHeadersMatcher {
  public function matchHeaders(Message $message): HeaderMatchListPerProvider {
    $headerMatchLists = [];
    /** @var \Geeks4change\UntrackEmailAnalyzer\Analyzer\NewsletterServicesMatcher\ServiceMatcherProvider $providerInfo */
    foreach (Globals::get()->getProviderRepository()->getProviderMatchers() as $providerInfo) {
      $matches = [];
      foreach ($providerInfo->getHeadersMatchers() as $headersMatcher) {
        $isMatch = $headersMatcher->matchHeaders($message);
        $headerValue = $message->getHeaderValue($headersMatcher->getName()) ?? '';
        $matches[] = new HeaderMatch($headersMatcher->getName(), $headerValue, $isMatch);
      }
      $headerMatchList = new HeaderMatchList($matches);
      if ($headerMatchList->any()) {
        $headerMatchLists[$providerInfo->getName()] = $headerMatchList;
      }
    }
    return new HeaderMatchListPerProvider($headerMatchLists);
  }

}

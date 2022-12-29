<?php

declare(strict_types=1);

namespace Geeks4change\BbndAnalyzer\Analyzer;

use Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult\UrlList;
use ZBateson\MailMimeParser\Message;

final class UnsubscribeLinkExtractor {

  public function extractUnsubscribeLink(Message $message): UrlList {
    $urlList = new UrlList();
    $unsubscribeHeader = $message->getHeader('List-Unsubscribe');
    if ($unsubscribeHeader) {
      $headerText = $unsubscribeHeader->getValue();
      // Example: <https://unsub.me/123>, <mailto:unsub+123@unsub.me>
      if (preg_match('~<(https?://.*?)>~u', $headerText, $matches)) {
        $url = $matches[1];
        $urlList->add($url);
      }
    }
    return $urlList;
  }

}

<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\MessageInfo;
use ZBateson\MailMimeParser\Header\DateHeader;
use ZBateson\MailMimeParser\IMessage;

final class MessageInfoExtractor {

  public function extract(IMessage $message): MessageInfo {
    $dateHeader = $message->getHeader('Date');
    assert($dateHeader instanceof DateHeader);
    $dateTime = $dateHeader->getDateTime();
    $timestamp = $dateTime->getTimestamp();
    return new MessageInfo($timestamp);
  }

}

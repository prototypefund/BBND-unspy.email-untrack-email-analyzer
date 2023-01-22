<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ListInfo\ListInfo;
use ZBateson\MailMimeParser\Header\AddressHeader;
use ZBateson\MailMimeParser\IMessage;

final class ListInfoExtractor {

  public function extract(IMessage $message): ListInfo {
    $fromHeader = $message->getHeader('From');
    if ($fromHeader instanceof AddressHeader) {
      $emailAddress = $fromHeader->getEmail();
      $emailLabel = $fromHeader->getPersonName();
    }
    elseif($fromHeader) {
      // @todo Log as error.
      $emailAddress = $fromHeader->getValue();
      $emailLabel = '';
    }
    else {
      // @todo Log as error.
      $emailAddress = '';
      $emailLabel = '';
    }
    $listIdHeader = $message->getHeader('List-Id');
    $listId = $this->parseListId($listIdHeader?->getValue(), $listIdLabel);
    // We do NOT parse tld here as that needs a caching service.
    // See untrack_email_storage.
    return new ListInfo($emailLabel, $emailAddress, $listId);
  }

  /**
   * @see https://www.ietf.org/rfc/rfc2919.txt
   */
  protected function parseListId(?string $rawListId, ?string &$listIdLabel = NULL): ?string {
    if ($rawListId && preg_match('~^(?<label>.*?) *<(?<id>.*)>$~u', $rawListId, $matches)) {
      $listIdLabel = $matches['label'] ?: NULL;
      return $matches['id'];
    }
    else {
      $listIdLabel = NULL;
      return NULL;
    }
  }

}

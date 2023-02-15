<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer2;

use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Result\Header\HeaderItem;
use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Result\Header\HeaderItemBag;
use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Result\Header\HeaderItemBagBuilder;
use ZBateson\MailMimeParser\IMessage;

final class HeaderItemExtractor {

  public function extract(IMessage $message): HeaderItemBag {
    $builder = new HeaderItemBagBuilder();
    foreach ($message->getAllHeaders() as $header) {
      foreach ($header->getParts() as $headerPart) {
        $builder->addItem(HeaderItem::create($header->getName(), $headerPart->getValue()));
      }
    }
    return $builder->freeze();
  }

}

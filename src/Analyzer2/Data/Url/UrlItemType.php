<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url;

enum UrlItemType: string {
  case Link = 'link';
  case Image = 'image';

  public function getXpath(): string {
    return match($this) {
      self::Link => '//a/@href',
      self::Image => '//img/@src',
    };
  }

}

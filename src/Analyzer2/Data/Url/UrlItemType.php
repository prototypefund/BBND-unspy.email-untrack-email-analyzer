<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url;

enum UrlItemType: string {
  case Link = 'link';
  case Image = 'image';

}

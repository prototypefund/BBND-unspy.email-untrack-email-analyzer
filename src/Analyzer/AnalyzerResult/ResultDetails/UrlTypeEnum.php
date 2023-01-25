<?php

declare(strict_types=1);
namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails;

enum UrlTypeEnum: string {
  case Link = 'link';
  case Image = 'image';
}

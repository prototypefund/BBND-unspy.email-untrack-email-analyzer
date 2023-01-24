<?php

declare(strict_types=1);
namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails;

enum DKIMStatusEnum: string {
  case Green = 'green';
  case Yellow = 'yellow';
  case Red = 'red';
}

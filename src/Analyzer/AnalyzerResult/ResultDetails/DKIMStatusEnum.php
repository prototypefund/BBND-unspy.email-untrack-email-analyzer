<?php

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails;

enum DKIMStatusEnum: string {
  case Green = 'green';
  case Yellow = 'yellow';
  case Red = 'red';
}

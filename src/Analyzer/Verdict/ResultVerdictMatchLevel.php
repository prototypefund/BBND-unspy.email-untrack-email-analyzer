<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\Verdict;

enum ResultVerdictMatchLevel: string {

  case Sure = 'sure';
  case Likely = 'likely';
  case Unknown = 'unknown';
  case Unlikely = 'unlikely';

}

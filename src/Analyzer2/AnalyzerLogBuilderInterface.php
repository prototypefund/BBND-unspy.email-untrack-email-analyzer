<?php

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer2;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\AnalyzerLog\AnalyzerLog;
use Psr\Log\LoggerInterface;

interface AnalyzerLogBuilderInterface extends LoggerInterface {

  public function freeze(): AnalyzerLog;

}

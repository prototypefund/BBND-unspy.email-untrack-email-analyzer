<?php

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\Log\AnalyzerLog;
use Psr\Log\LoggerInterface;

interface AnalyzerLogBuilderInterface extends LoggerInterface {

  public function freeze(): AnalyzerLog;

}

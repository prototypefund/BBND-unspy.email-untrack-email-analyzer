#!/usr/bin/env php
<?php

use Geeks4change\BbndAnalyzer\Analyzer;
use Geeks4change\BbndAnalyzer\DebugAnalysisBuilder;

$fileId = $argv[1] ?? NULL;
$fileName = dirname(__DIR__) . "/tests/examples/$fileId.eml";
$fileContent = file_get_contents($fileName);

if (!$fileId || !$fileContent) {
  print("Need valid fileId");
  die(1);
}

include 'vendor/autoload.php';
$analyzer = new Analyzer();
$analysis = new DebugAnalysisBuilder();
$analyzer->analyze($analysis, $fileContent);
print $analysis->getDebugOutput();
print "\n";

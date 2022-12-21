#!/usr/bin/env php
<?php

use Geeks4change\BbndAnalyzer\Analyzer;
use Geeks4change\BbndAnalyzer\NullAnalysisBuilder;

include 'vendor/autoload.php';
$analyzer = new Analyzer();
$analysis = new NullAnalysisBuilder();
$analyzer->analyze($analysis, file_get_contents('/home/merlin/Code-Incubator/bbnd/_Emails/Prototype Fund - Infoletter.eml'));
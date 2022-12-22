<?php

namespace Geeks4change\tests\BbndAnalyzer;

use Geeks4change\BbndAnalyzer\Analyzer;
use Geeks4change\BbndAnalyzer\DebugAnalysisBuilder;
use Geeks4change\BbndAnalyzer\Utility\ThrowMethodTrait;
use PHPUnit\Framework\TestCase;

class AnalizerTest extends TestCase {

  use ThrowMethodTrait;

  /**
   * @dataProvider provideEmailExamples
   */
  public function testAnalyzerWithExamples(string $id, string $email, string $expected): void {
    $analyzer = new Analyzer();
    $analysis = new DebugAnalysisBuilder();
    $analyzer->analyze($analysis, $email);
    self::assertEquals($expected, $analysis->getDebugOutput());
  }

  public function provideEmailExamples(): \Iterator {
    $examplesDir = dirname(__DIR__) . '/examples';
    foreach (glob($examplesDir . '/*.eml') as $emailFile) {
      $id = basename($emailFile, '.eml');
      $email = file_get_contents($emailFile);
      $expectedFile = "$examplesDir/$id.expected.txt";
      $expected = file_get_contents($expectedFile);
      yield [$id, $email, $expected];
    }
  }

}

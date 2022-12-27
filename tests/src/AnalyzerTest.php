<?php

namespace Geeks4change\tests\BbndAnalyzer;

use Geeks4change\BbndAnalyzer\Analyzer\Analyzer;
use Geeks4change\BbndAnalyzer\DebugAnalysisBuilder;
use Geeks4change\BbndAnalyzer\TestHelpers\TestSummaryTrait;
use Geeks4change\BbndAnalyzer\Utility\ThrowMethodTrait;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml\Yaml;

class AnalyzerTest extends TestCase {

  use ThrowMethodTrait;
  use TestSummaryTrait;

  /**
   * @dataProvider provideEmailExamples
   */
  public function testAnalyzerWithExamples(string $id, string $email, array $expected): void {
    $analyzer = new Analyzer();
    $result = $analyzer->analyze($email);
    $testSummary = $result->getTestSummary();
    $this->assertTestSummaryContains($expected, $testSummary);
  }

  public function provideEmailExamples(): \Iterator {
    $examplesDir = dirname(__DIR__) . '/examples';
    foreach (glob($examplesDir . '/*.eml') as $emailFile) {
      $id = basename($emailFile, '.eml');
      $email = file_get_contents($emailFile);
      $expectedFile = "$examplesDir/$id.expected.txt";
      $expectedAsYaml = file_get_contents($expectedFile);
      $expected = Yaml::parse($expectedAsYaml);
      yield [$id, $email, $expected];
    }
  }

}

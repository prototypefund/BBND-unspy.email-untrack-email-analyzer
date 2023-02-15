<?php

namespace Geeks4change\tests\UntrackEmailAnalyzer;

use Geeks4change\UntrackEmailAnalyzer\Api;
use Geeks4change\UntrackEmailAnalyzer\DirInfo;
use Geeks4change\UntrackEmailAnalyzer\Utility\ObjectToArray;
use PHPUnit\Framework\TestCase;

class AnalyzerTest extends TestCase {

  /**
   * @dataProvider provideEmailTestCases
   */
  public function testAnalyzer(string $id, string $email, array $expected): void {
    $analyzer = Api::getDebugAnalyzer();
    $result = $analyzer->analyze($email, catchAndLogExceptions: FALSE);
    $testSummary = ObjectToArray::convert($result);
    $this->assertSame($expected, $testSummary);
  }

  public function provideEmailTestCases(): \Iterator {
    return DirInfo::provideEmailTestCases();
  }

}

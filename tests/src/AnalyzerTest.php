<?php

namespace Geeks4change\tests\UntrackEmailAnalyzer;

use Geeks4change\UntrackEmailAnalyzer\Api;
use Geeks4change\UntrackEmailAnalyzer\DirInfo;
use Geeks4change\UntrackEmailAnalyzer\Matcher\MatcherRestrictor;
use Geeks4change\UntrackEmailAnalyzer\Utility\ObjectToArray;
use PHPUnit\Framework\TestCase;

class AnalyzerTest extends TestCase {

  /**
   * @dataProvider provideEmailTestCases
   */
  public function testAnalyzer(string $testId, string $email, array $expected): void {
    [$providerId, $testSubId] = explode(':', $testId);
    $analyzer = Api::getDebugAnalyzer();
    $result = $analyzer->analyze($email, catchAndLogExceptions: FALSE);
    $result = MatcherRestrictor::restrictToMatcher($providerId, $result);
    $testSummary = ObjectToArray::convert($result);
    $this->assertSame($expected, $testSummary);
  }

  public function provideEmailTestCases(): \Iterator {
    return DirInfo::provideEmailTestCases();
  }

}

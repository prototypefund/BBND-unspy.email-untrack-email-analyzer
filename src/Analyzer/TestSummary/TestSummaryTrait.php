<?php

namespace Geeks4change\BbndAnalyzer\Analyzer\TestSummary;

trait TestSummaryTrait {

  /**
   * Robustly compare test summary with expected.
   *
   * The idea is that the test should still match, when the actual test summary
   * data has added keys. Therefore, the actual data is filtered via
   * ::intersectKeyRecursive.
   * A former approach used ::diffAssocRecursive, which works as good or better,
   * but duplicating the PHPUnit pretty-printing of failure exceptions was too
   * much a hassle.
   */
  protected function assertTestSummaryContains(array $expected, array $actual, string $message = ''): void {
    $this->assertEquals($expected, self::intersectKeyRecursive($actual, $expected));
  }

  private static function diffAssocRecursive(array $array1, array $array2) {
    $difference = [];
    foreach ($array1 as $key => $value) {
      if (is_array($value)) {
        if (!array_key_exists($key, $array2) || !is_array($array2[$key])) {
          $difference[$key] = $value;
        }
        else {
          $new_diff = static::diffAssocRecursive($value, $array2[$key]);
          if (!empty($new_diff)) {
            $difference[$key] = $new_diff;
          }
        }
      }
      elseif (!array_key_exists($key, $array2) || $array2[$key] !== $value) {
        $difference[$key] = $value;
      }
    }
    return $difference;
  }

  private static function intersectKeyRecursive(array $array1, array $array2) {
    $array1 = array_intersect_key($array1, $array2);
    foreach ($array1 as $key => &$value) {
      if (is_array($value) && is_array($array2[$key])) {
        $value = self::intersectKeyRecursive($value, $array2[$key]);
      }
    }
    return $array1;
  }
}
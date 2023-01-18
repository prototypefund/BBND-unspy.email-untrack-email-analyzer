<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer;

use Geeks4change\UntrackEmailAnalyzer\Utility\FileTool;
use loophp\collection\Collection;

/**
 * @internal
 */
final class DirInfo {

  public static function getProjectRoot(): string {
    return dirname(__DIR__);
  }

  protected static function getPatternsDir(): string {
    return self::getProjectRoot() . '/patterns';
  }

  /**
   * @return array<string, string>
   */
  public static function getPatternFilePaths(): array {
    $unfilteredFilePaths = glob(DirInfo::getPatternsDir() . '/*/pattern.yml');
    /** @noinspection PhpUnnecessaryLocalVariableInspection */
    // Re-key, filter, validate.
    $indexedFilteredFilePaths = Collection::fromIterable($unfilteredFilePaths)
      ->associate(static fn($id, $filePath) => basename(dirname($filePath)))
      ->filter(static fn($filePath, $id) => !str_starts_with($id, '_'))
      ->filter(static fn($filePath, $id) => preg_match('/[a-z0-9_]+/u', $id) || throw new \LogicException("Invalid pattern ID: $id"))
      ->all(FALSE)
    ;
    return $indexedFilteredFilePaths;
  }

  public static function getTestEmailFileNames(): \Iterator {
    foreach (self::getPatternFilePaths() as $patternId => $patternFile) {
      $patternDir = dirname($patternFile);
      foreach (glob("$patternDir/tests/*.eml") as $emailFile) {
        $testName = basename($emailFile, '.eml');
        $expectedFile = dirname($emailFile) . "/$testName.expected.yml";
        yield "$patternId:$testName" => [$emailFile, $expectedFile];
      }
    }
  }

  public static function provideEmailTestCases(): \Iterator {
    foreach (self::getTestEmailFileNames() as $id => [$emailFile, $expectedFile]) {
      $email = FileTool::getFileContents($emailFile);
      $expected = FileTool::getYamlArray($expectedFile);
      yield $id => [$id, $email, $expected];
    }
  }

}

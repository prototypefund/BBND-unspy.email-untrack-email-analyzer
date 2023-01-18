<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer;

use Geeks4change\UntrackEmailAnalyzer\Utility\FileYaml;
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

  public static function getTestEmailsDir(): string {
    return self::getProjectRoot() . '/tests/examples';
  }

  public static function getTestEmailFileNames(): \Iterator {
    $examplesDir = self::getTestEmailsDir();
    foreach (glob($examplesDir . '/*.eml') as $emailFile) {
      $id = basename($emailFile, '.eml');
      $expectedFile = "$examplesDir/$id.expected.txt";
      yield $id => [$emailFile, $expectedFile];
    }
  }

  public static function provideEmailTestCases(): \Iterator {
    $examplesDir = self::getTestEmailsDir();
    foreach (glob($examplesDir . '/*.eml') as $id => [$emailFile, $expectedFile]) {
      $email = file_get_contents($emailFile);
      $expected = FileYaml::get($expectedFile);
      yield $id => [$id, $email, $expected];
    }
  }

}

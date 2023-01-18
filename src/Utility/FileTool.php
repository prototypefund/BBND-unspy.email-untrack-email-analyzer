<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Utility;

use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

final class FileTool {

  public static function getYamlData(string $filePath): mixed {
    $yaml = self::getFileContents($filePath);
    try {
      $array = Yaml::parse($yaml);

    } catch (ParseException $exception) {
      throw new \LogicException("Invalid Yaml in $filePath", 0, $exception);
    }
    return $array;
  }

  public static function getYamlArray(string $filePath): array {
    $array = self::getYamlData($filePath);
    if (!is_array($array)) {
      throw new \LogicException("Not an array: $filePath");
    }
    return $array;
  }

  public static function getFileContents(string $filePath): string {
    $contents = file_get_contents($filePath);
    if ($contents === FALSE) {
      throw new \LogicException("Can not read $filePath");
    }
    return $contents;
  }

}

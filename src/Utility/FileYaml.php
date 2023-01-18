<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Utility;

use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

final class FileYaml {

  public static function get(string $filePath): mixed {
    $yaml = file_get_contents($filePath);
    if ($yaml === FALSE) {
      throw new \LogicException("Can not read $filePath");
    }
    try {
      $array = Yaml::parse($yaml);

    } catch (ParseException $exception) {
      throw new \LogicException("Invalid Yaml in $filePath", 0, $exception);
    }
    return $array;
  }

}

<?php

declare(strict_types=1);

namespace Geeks4change\BbndAnalyzer\Pattern;

use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

final class ToolPatternRepository {

  protected ToolPatternCollection $patternCollection;

  public function getToolPatternCollection(): ToolPatternCollection {
    if (!isset($this->patternCollection)) {
      $directory = dirname(dirname(__DIR__)) . '/patterns';
      $patternCollectionBuilder = ToolPatternCollection::builder();
      $filePaths = glob("$directory/*.yml");
      foreach ($filePaths as $filePath) {
        $id = basename($filePath, '.yml');
        if (!preg_match('/[a-z0-9_]+/u', $id)) {
          throw new \LogicException("Invalid pattern ID: $id");
        }
        if (substr($id, 0, 1) === '_') {
          continue;
        }
        $yaml = file_get_contents($filePath);
        try {
          $array = Yaml::parse($yaml);

        } catch (ParseException $exception) {
          throw new \LogicException("Oops in $id.yml", 0, $exception);
        }
        $pattern = ToolPattern::fromArray($id, $array);
        $patternCollectionBuilder->add($pattern);
      }
      $this->patternCollection = $patternCollectionBuilder->freeze();
    }
    return $this->patternCollection;
  }

}
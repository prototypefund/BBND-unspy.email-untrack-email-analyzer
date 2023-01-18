<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\NewsletterServicesMatcher;

use Geeks4change\UntrackEmailAnalyzer\DirInfo;
use loophp\collection\Collection;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

final class ServiceMatcherProviderRepository {

  protected ServiceMatcherProviderCollection $serviceMatcherProviderCollection;

  public function getServiceMatcherProviderCollection(): ServiceMatcherProviderCollection {
    if (!isset($this->serviceMatcherProviderCollection)) {
      $serviceMatcherProviderCollectionBuilder = ServiceMatcherProviderCollection::builder();
      foreach ($this->getPatternFilePaths() as $id => $filePath) {
        $array = $this->parseYaml($filePath);
        $serviceMatcherProvider = ServiceMatcherProvider::fromArray($id, $array);
        $serviceMatcherProviderCollectionBuilder->add($serviceMatcherProvider);
      }
      $this->serviceMatcherProviderCollection = $serviceMatcherProviderCollectionBuilder->freeze();
    }
    return $this->serviceMatcherProviderCollection;
  }

  /**
   * @return array<string, string>
   */
  public function getPatternFilePaths(): array {
    $directory = DirInfo::getNewsletterServiceInfoDir();
    // Re-key, filter, validate.
    /** @noinspection PhpUnnecessaryLocalVariableInspection */
    $indexedFilteredFilePaths = Collection::fromIterable(glob("$directory/*/pattern.yml"))
      ->associate(static fn($id, $filePath) => basename(dirname($filePath)))
      ->filter(static fn($filePath, $id) => !str_starts_with($id, '_'))
      ->filter(static fn($filePath, $id) => preg_match('/[a-z0-9_]+/u', $id) || throw new \LogicException("Invalid pattern ID: $id"))
      ->all(FALSE)
      ;
    return $indexedFilteredFilePaths;
  }

  public function parseYaml(string $filePath): array {
    $yaml = file_get_contents($filePath);
    try {
      $array = Yaml::parse($yaml);

    } catch (ParseException $exception) {
      throw new \LogicException("Oops in $filePath", 0, $exception);
    }
    if (!is_array($array)) {
      throw new \LogicException("Not an array: $filePath");
    }
    return $array;
  }

}

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
        $yaml = file_get_contents($filePath);
        try {
          $array = Yaml::parse($yaml);

        } catch (ParseException $exception) {
          throw new \LogicException("Oops in $id.yml", 0, $exception);
        }
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
    $indexedFilteredFilePaths = Collection::fromIterable(glob("$directory/*.yml"))
      ->associate(static fn($id, $filePath) => basename($filePath, '.yml'))
      ->filter(static fn($filePath, $id) => !str_starts_with($id, '_'))
      ->filter(static fn($filePath, $id) => preg_match('/[a-z0-9_]+/u', $id) || throw new \LogicException("Invalid pattern ID: $id"))
      ;
    return $indexedFilteredFilePaths->all(FALSE);
  }

}
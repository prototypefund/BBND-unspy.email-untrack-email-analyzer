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
      foreach (DirInfo::getPatternFilePaths() as $id => $filePath) {
        $array = $this->parseYaml($filePath);
        $serviceMatcherProvider = ServiceMatcherProvider::fromArray($id, $array);
        $serviceMatcherProviderCollectionBuilder->add($serviceMatcherProvider);
      }
      $this->serviceMatcherProviderCollection = $serviceMatcherProviderCollectionBuilder->freeze();
    }
    return $this->serviceMatcherProviderCollection;
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

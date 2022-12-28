<?php

declare(strict_types=1);

namespace Geeks4change\BbndAnalyzer\Analyzer\NewsletterServicesMatcher;

use Geeks4change\BbndAnalyzer\DirInfo;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

final class ServiceMatcherProviderRepository {

  protected ServiceMatcherProviderCollection $serviceMatcherProviderCollection;

  public function getServiceMatcherProviderCollection(): ServiceMatcherProviderCollection {
    if (!isset($this->serviceMatcherProviderCollection)) {
      $directory = DirInfo::getNewsletterServiceInfoDir();
      $serviceMatcherProviderCollectionBuilder = ServiceMatcherProviderCollection::builder();
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
        $serviceMatcherProvider = ServiceMatcherProvider::fromArray($id, $array);
        $serviceMatcherProviderCollectionBuilder->add($serviceMatcherProvider);
      }
      $this->serviceMatcherProviderCollection = $serviceMatcherProviderCollectionBuilder->freeze();
    }
    return $this->serviceMatcherProviderCollection;
  }

}
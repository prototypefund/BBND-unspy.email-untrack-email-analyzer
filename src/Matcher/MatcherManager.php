<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Matcher;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\Result\Header\HeaderItemBag;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\Result\Header\HeaderItemInfoBag;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\Result\Header\HeaderItemInfoBagBuilder;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\Result\Url\UrlItemInfoBag;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\Result\Url\UrlItemInfoBagBuilder;
use Geeks4change\UntrackEmailAnalyzer\Utility\FileTool;

final class MatcherManager {

  protected array $matchers;

  public function matchHeaders(HeaderItemBag $headerItemBag): HeaderItemInfoBag {
    $builder = HeaderItemInfoBagBuilder::fromHeaderItemBag($headerItemBag);
    foreach ($this->getMatchers() as $matcher) {
      foreach ($headerItemBag->items as $item) {
        $match = $matcher->matchHeader($item);
        if (isset($match)) {
          $builder->addMatch($item, $match);
        }
      }
    }
    return $builder->freeze();
  }

  public function matchUrls(UrlItemInfoBag $urlItemInfoBag): UrlItemInfoBag {
    $builder = UrlItemInfoBagBuilder::fromUrlItemInfoBag($urlItemInfoBag);
    foreach ($urlItemInfoBag->urlItemInfos as $urlItemInfo) {
      $urlItem = $urlItemInfo->urlItem;
      foreach ($this->getMatchers() as $id => $matcher) {
        if ($match = $matcher->matchUrl($urlItem)) {
          $builder->forUrlItem($urlItem)->addMatch($match);
        }
      }
    }
    return $builder->freeze();
  }

  /**
   * @return array<string, \Geeks4change\UntrackEmailAnalyzer\Matcher\MatcherInterface>
   */
  public function getMatchers(): array {
    return $this->matchers ?? ($this->matchers = $this->discoverMatchers());
  }

  /**
   * @return array<string, \Geeks4change\UntrackEmailAnalyzer\Matcher\MatcherInterface>
   */
  protected function discoverMatchers(): array {
    $matchers = [];
    foreach ($this->getDirectoriesById() as $id => $directory) {
      $name = ucfirst($id);
      $namespace = $this->getNamespace($id);
      $class = "{$namespace}\\{$name}Matcher";
      if (class_exists($class)) {
        $matcher = new ($class)();
        if ($matcher instanceof MatcherInterface) {
          $id = $matcher->getId();
          $matchers[$id] = $matcher;
        }
        else {
          throw new \LogicException("Invalid matcher dir: $directory");
        }
      }
    }
    return $matchers;
  }

  public function getTestEmailFileNames(): \Iterator {
    foreach ($this->getDirectoriesById() as $id => $directory) {
      foreach (glob("$directory/tests/*.eml") as $emailFile) {
        $testName = basename($emailFile, '.eml');
        $expectedFile = dirname($emailFile) . "/$testName.expected.yml";
        yield "$id:$testName" => [$emailFile, $expectedFile];
      }
    }
  }

  public function provideEmailTestCases(): \Iterator {
    foreach ($this->getTestEmailFileNames() as $id => [$emailFile, $expectedFile]) {
      $email = FileTool::getFileContents($emailFile);
      $expected = file_exists($expectedFile) ?
        FileTool::getYamlArray($expectedFile) : [];
      yield $id => [$id, $email, $expected];
    }
  }

  protected function getDirectoriesById(): array {
    $containerDir = __DIR__;
    $directories = glob("$containerDir/*", GLOB_ONLYDIR);
    $ids = array_map(fn($dir) => basename($dir), $directories);
    return array_combine($ids, $directories);
  }

  protected function getNamespace(string $id): string {
    $containerNamespace = __NAMESPACE__;
    return "{$containerNamespace}\\{$id}";
  }

}

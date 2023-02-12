<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Matcher2;

final class MatcherManager {

  protected array $matcherNames;

  protected array $matchers;

  /**
   * @return array<string, \Geeks4change\UntrackEmailAnalyzer\Matcher2\MatcherInterface>
   */
  public function getMatchers(): array {
    return $this->matchers ?? ($this->matchers = $this->discoverMatchers());
  }

  /**
   * @return array<string, \Geeks4change\UntrackEmailAnalyzer\Matcher2\MatcherInterface>
   */
  protected function discoverMatchers(): array {
    $matcherContainerDir = __DIR__;
    $matcherContainerNamespace = __NAMESPACE__;
    $matchers = [];
    foreach (glob("$matcherContainerDir/*", GLOB_ONLYDIR) as $matcherDir) {
      $name = basename($matcherDir);
      $matcher = new ("$matcherContainerNamespace\{$name}\{$name}Matcher")();
      if ($matcher instanceof MatcherInterface) {
        $id = $matcher->getId();
        $matchers[$id] = $name;
      }
      else {
        throw new \LogicException("Invalid matcher dir: $matcherDir");
      }
    }
    return $matchers;
  }

}

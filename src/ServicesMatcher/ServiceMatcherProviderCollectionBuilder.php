<?php

declare(strict_types=1);

namespace Geeks4change\BbndAnalyzer\ServicesMatcher;

final class ServiceMatcherProviderCollectionBuilder {

  /**
   */
  protected array $patterns = [];

  protected \Closure $constructor;

  /**
   * Nothing to see here. Use PatternCollection::builder.
   */
  public function __construct(\Closure $constructor) {
    $this->constructor = $constructor;
  }

  public function add(ServiceMatcherProvider $pattern) {
    $this->patterns[$pattern->getName()] = $pattern;
  }

  public function freeze(): ServiceMatcherProviderCollection {
    return ($this->constructor)($this->patterns);
  }

}
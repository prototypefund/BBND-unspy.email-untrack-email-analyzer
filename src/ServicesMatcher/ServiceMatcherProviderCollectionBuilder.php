<?php

declare(strict_types=1);

namespace Geeks4change\BbndAnalyzer\ServicesMatcher;

final class ServiceMatcherProviderCollectionBuilder {

  /**
   */
  protected array $serviceMatcherProviders = [];

  protected \Closure $constructor;

  /**
   * Nothing to see here. Use PatternCollection::builder.
   */
  public function __construct(\Closure $constructor) {
    $this->constructor = $constructor;
  }

  public function add(ServiceMatcherProvider $serviceMatcherProvider) {
    $this->serviceMatcherProviders[$serviceMatcherProvider->getName()] = $serviceMatcherProvider;
  }

  public function freeze(): ServiceMatcherProviderCollection {
    return ($this->constructor)($this->serviceMatcherProviders);
  }

}
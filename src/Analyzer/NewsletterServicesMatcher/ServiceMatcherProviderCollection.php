<?php

declare(strict_types=1);

namespace Geeks4change\BbndAnalyzer\Analyzer\NewsletterServicesMatcher;

use Geeks4change\BbndAnalyzer\Utility\ArrayAccessTrait;

class ServiceMatcherProviderCollection implements \IteratorAggregate, \ArrayAccess, \Countable {

  use ArrayAccessTrait;

  /**
   * @var array<\Geeks4change\BbndAnalyzer\Analyzer\NewsletterServicesMatcher\ServiceMatcherProvider>
   */
  protected array $patterns;

  /**
   * @param \Geeks4change\BbndAnalyzer\Analyzer\NewsletterServicesMatcher\ServiceMatcherProvider[] $patterns
   */
  private function __construct(array $patterns) {
    $this->patterns = $patterns;
  }

  /**
   * The only way to construct a PatternCollection.
   */
  public static function builder(): ServiceMatcherProviderCollectionBuilder {
    return new ServiceMatcherProviderCollectionBuilder(\Closure::fromCallable(fn(array $patterns) => new self($patterns)));
  }

  protected function &getInnerArray(): array {
    return $this->patterns;
  }

}
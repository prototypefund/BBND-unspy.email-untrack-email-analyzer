<?php

declare(strict_types=1);

namespace Geeks4change\BbndAnalyzer\ServicesMatcher;

use Geeks4change\BbndAnalyzer\Utility\ArrayAccessTrait;

class ToolPatternCollection implements \IteratorAggregate, \ArrayAccess, \Countable {

  use ArrayAccessTrait;

  /**
   * @var array<\Geeks4change\BbndAnalyzer\ServicesMatcher\ToolPattern>
   */
  protected array $patterns;

  /**
   * @param \Geeks4change\BbndAnalyzer\ServicesMatcher\ToolPattern[] $patterns
   */
  private function __construct(array $patterns) {
    $this->patterns = $patterns;
  }

  /**
   * The only way to construct a PatternCollection.
   */
  public static function builder(): ToolPatternCollectionBuilder {
    return new ToolPatternCollectionBuilder(\Closure::fromCallable(fn(array $patterns) => new self($patterns)));
  }

  protected function &getInnerArray(): array {
    return $this->patterns;
  }

}
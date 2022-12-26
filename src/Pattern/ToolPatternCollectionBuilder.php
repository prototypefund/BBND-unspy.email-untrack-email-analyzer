<?php

declare(strict_types=1);

namespace Geeks4change\BbndAnalyzer\Pattern;

final class ToolPatternCollectionBuilder {

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

  public function add(ToolPattern $pattern) {
    $this->patterns[$pattern->getName()] = $pattern;
  }

  public function freeze(): ToolPatternCollection {
    return ($this->constructor)($this->patterns);
  }

}
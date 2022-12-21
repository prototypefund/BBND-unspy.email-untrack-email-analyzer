<?php

namespace Geeks4change\BbndAnalyzer\Pattern;

class ToolPatternIterator extends \ArrayIterator {

  public function offsetGet($key): ToolPattern {
    return parent::offsetGet($key);
  }

  public function current(): ToolPattern {
    return parent::current();
  }

}
<?php

namespace Geeks4change\BbndAnalyzer\ServicesMatcher;

class ToolPatternIterator extends \ArrayIterator {

  public function offsetGet($key): ServiceMatcherProvider {
    return parent::offsetGet($key);
  }

  public function current(): ServiceMatcherProvider {
    return parent::current();
  }

}
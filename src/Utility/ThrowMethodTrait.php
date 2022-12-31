<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Utility;

/**
 * Provides a throw method, obsolete in PHP8 by throw as expression.
 *
 * Provide static method, so can be used both statically and in instance.
 */
trait ThrowMethodTrait {

  protected static function throw(\Throwable $throwable) {
    throw $throwable;
  }

  protected static function throwRuntime(string $message = '') {
    throw new \RuntimeException($message);
  }

  protected static function throwLogic(string $message = '') {
    throw new \LogicException($message);
  }

  protected static function throwUnexpectedValue(string $message = '') {
    throw new \UnexpectedValueException($message);
  }

}

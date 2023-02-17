<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer;

final class Globals {

  public static bool $isDebug = FALSE;

  protected static ?self $singleton;

  public static function get(): self {
    if (!isset(self::$singleton)) {
      self::$singleton = new static();
    }
    return self::$singleton;
  }

  public static function deleteAll() {
    self::$singleton = NULL;
  }

  protected CnameResolver $domainAliasesResolver;

  public function getCnameResolver() {
    if (!isset($this->domainAliasesResolver)) {
      $this->domainAliasesResolver = new CnameResolver();
    }
    return $this->domainAliasesResolver;
  }
}

<?php

declare(strict_types=1);

namespace Geeks4change\BbndAnalyzer;

final class DirInfo {

  public static function getProjectRoot(): string {
    return dirname(__DIR__);
  }

  public static function getNewsletterServiceInfoDir(): string {
    return self::getProjectRoot() . '/patterns';
  }

}

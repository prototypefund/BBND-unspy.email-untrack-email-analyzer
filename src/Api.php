<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\Analyzer;
use loophp\collection\Collection;

final class Api {

  public static function getAnalyzer2(): Analyzer {
    return new Analyzer();
  }

  public static function getDebugAnalyzer(): Analyzer {
    return (new Analyzer())->setUnitTestMode();
  }

  public static function getTestEmailFileNames(): array {
    return Collection::fromIterable(DirInfo::getTestEmailFileNames())
      ->map(fn($filePaths) => $filePaths[0])
      ->all(FALSE);
  }

}

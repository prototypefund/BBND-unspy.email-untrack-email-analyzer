<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\Analyzer;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\RedirectDetector;
use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Analyzer2;
use Geeks4change\UntrackEmailAnalyzer\RedirectResolver\NullRedirectResolver;
use loophp\collection\Collection;

final class Api {

  public static function getAnalyzer2(): Analyzer2 {
    return new Analyzer2();
  }

  public static function getAnalyzer(): Analyzer {
    return new Analyzer(new RedirectDetector());
  }

  public static function getDebugAnalyzer(): Analyzer {
    return new Analyzer(new RedirectDetector(new NullRedirectResolver()));
  }

  public static function getTestEmailFileNames(): array {
    return Collection::fromIterable(DirInfo::getTestEmailFileNames())
      ->map(fn($filePaths) => $filePaths[0])
      ->all(FALSE);
  }

}

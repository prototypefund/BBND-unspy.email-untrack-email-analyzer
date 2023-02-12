<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url;

use Geeks4change\UntrackEmailAnalyzer\Matcher2\Matcher;

final class UrlItemMatch {

  public function __construct(
    public readonly string $matcherId,
    public readonly UrlItemMatchType $type,
  ) {}

}

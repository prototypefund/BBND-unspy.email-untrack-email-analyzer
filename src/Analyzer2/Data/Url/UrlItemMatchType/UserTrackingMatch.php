<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\UrlItemMatchType;

final class UserTrackingMatch extends UrlItemMatchBase {

  public function __construct(
    public string $matcherId,
  ) {}

}

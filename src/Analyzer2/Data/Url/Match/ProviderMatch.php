<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\Match;

final class ProviderMatch {

  public function __construct(
    public readonly string $providerId,
    public readonly string $name,
    public readonly bool   $isUserTracking,
    public readonly bool   $noRedirectCheck,
  ) {}

}

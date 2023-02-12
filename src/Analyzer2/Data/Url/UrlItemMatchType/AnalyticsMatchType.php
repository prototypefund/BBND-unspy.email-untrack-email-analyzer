<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\UrlItemMatchType;

enum AnalyticsMatchType: string {
  // Contains only known utm_[something_pseudonymous].
  case LooksHarmless = 'looks_harmless';
  // Contains other keys.
  case NeedsResearch = 'needs_research';
}

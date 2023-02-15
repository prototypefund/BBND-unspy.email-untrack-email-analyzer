<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer2\Result\Url\Analytics;

enum AnalyticsMatchType: string {
  // Contains only well-known utm_[something_pseudonymous].
  case LooksHarmless = 'looks_harmless';
  // Contains unknown keys.
  case NeedsResearch = 'needs_research';
  // Contains well-known user tracking keys.
  case LooksLikeUserTracking = 'looks_like_user_tracking';
}

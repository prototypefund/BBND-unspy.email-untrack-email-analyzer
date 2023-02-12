<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url;

enum UrlItemMatchType {
  case UserTracking;
  case Analytics;
  case Unsubscribe;
}

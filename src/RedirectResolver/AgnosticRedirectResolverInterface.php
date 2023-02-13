<?php

namespace Geeks4change\UntrackEmailAnalyzer\RedirectResolver;

interface AgnosticRedirectResolverInterface {

  /**
   * Agnostic wrt data types.
   */
  public function resolveRedirects(array $urls): array;

}
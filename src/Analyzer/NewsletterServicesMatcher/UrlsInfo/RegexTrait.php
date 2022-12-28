<?php

namespace Geeks4change\BbndAnalyzer\Analyzer\NewsletterServicesMatcher\UrlsInfo;

trait RegexTrait {

  /**
   * Get Regex (public for debugging).
   */
  protected function doGetRegex($pattern, $separator): string {
    $quotedPattern = preg_quote($pattern, '~');
    $wildcard = $separator ? "[^{$separator}]+" : '.+';
    $regexPart = preg_replace('#\\\\{.*?\\\\}#u', $wildcard, $quotedPattern);
    /** @noinspection PhpUnnecessaryLocalVariableInspection */
    $regex = "~^{$regexPart}($|[?]|[&]|#)~u";
    return $regex;
  }

}
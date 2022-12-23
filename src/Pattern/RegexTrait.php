<?php

namespace Geeks4change\BbndAnalyzer\Pattern;

trait RegexTrait {

  protected function getRegex($pattern, $separator): string {
    $quotedPattern = preg_quote($pattern, '~');
    $wildcard = $separator ? "[^{$separator}]+" : '.+';
    $regexPart = preg_replace('#\\\\{.*?\\\\}#u', $wildcard, $quotedPattern);
    /** @noinspection PhpUnnecessaryLocalVariableInspection */
    $regex = "~^{$regexPart}($|[?]|[&]|#)~u";
    return $regex;
  }

}
<?php

namespace Geeks4change\BbndAnalyzer\Pattern;

trait RegexTrait {

  public function getRegex($pattern, $separator): string {
    $quotedPattern = preg_quote($pattern, '~');
    $regexPart = preg_replace('#\\\\{.*?\\\\}#u', "[^{$separator}]+", $quotedPattern);
    /** @noinspection PhpUnnecessaryLocalVariableInspection */
    $regex = "~^{$regexPart}($|[?]|[&]|#)~u";
    return $regex;
  }

}
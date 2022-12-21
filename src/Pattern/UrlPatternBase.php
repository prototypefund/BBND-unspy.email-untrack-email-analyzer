<?php

namespace Geeks4change\BbndAnalyzer\Pattern;

use Geeks4change\BbndAnalyzer\DomElement\DomElementInterface;

abstract class UrlPatternBase {

  protected string $pattern;

  protected string $tracking;

  protected string $type;

  public function __construct(string $pattern, string $tracking, string $type) {
    $this->pattern = $pattern;
    $this->tracking = $tracking;
    $this->type = $type;
  }


  #[\ReturnTypeWillChange]
  public static function fromItem($value, $key) {
    return new static($value['pattern'], $value['tracking'] ?? 'unknown', $value['type'] ?? 'other');
  }

  protected function doMatches(DomElementInterface $domElement): bool {
    $quotedPattern = preg_quote($this->pattern, '#');
    $regexPart = preg_replace('#\\\\{.*?\\\\}#u', '[^/]+', $quotedPattern);
    $url = $domElement->getUrl()->getUrl();
    $regex = "#{$regexPart}$#u";
    /** @noinspection PhpUnnecessaryLocalVariableInspection */
    $match = preg_match($regex, $url);
    return $match;
  }

}

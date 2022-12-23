<?php

declare(strict_types=1);

namespace Geeks4change\BbndAnalyzer\Pattern;

use Geeks4change\BbndAnalyzer\DomElement\DomElementInterface;

abstract class UrlPatternBase {

  use RegexTrait;

  protected string $name;

  protected string $pattern;

  protected string $tracking;

  protected string $type;

  public function __construct(string $name, string $pattern, string $tracking, string $type) {
    $this->name = $name;
    $this->pattern = $pattern;
    $this->tracking = $tracking;
    $this->type = $type;
  }


  /**
   * @return static
   */
  #[\ReturnTypeWillChange]
  public static function fromItem($value, string $key) {
    return new static($key, $value['pattern'], $value['tracking'] ?? 'unknown', $value['type'] ?? 'other');
  }

  protected function doMatches(DomElementInterface $domElement): bool {
    // @fixme Separator must change in different parts of URL.
    $regex = $this->getRegex($this->pattern, '/');
    $pathAndQuery = $domElement->getUrl()->getPathAndQuery();
    $effectiveHosts = $domElement->getUrl()->getEffectiveHosts();
    foreach ($effectiveHosts as $effectiveHost) {
      $relevantUrl = "{$effectiveHost}{$pathAndQuery}";
      if (preg_match($regex, $relevantUrl)) {
        return TRUE;
      }
    }
    return FALSE;
  }

}

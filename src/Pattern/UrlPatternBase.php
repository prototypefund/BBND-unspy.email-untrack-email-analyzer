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
    $regex = $this->getRegex($this->pattern);
    $pathAndQuery = $domElement->getUrl()->getPathAndQuery();
    $effectiveHosts = $domElement->getUrl()->getEffectiveHosts();
    foreach ($effectiveHosts as $effectiveHost) {
      // @todo Do this in Url::getRelevantUrlPartForAllCNames
      $relevantUrl = "{$effectiveHost}{$pathAndQuery}";
      if (preg_match($regex, $relevantUrl)) {
        return TRUE;
      }
    }
    return FALSE;
  }

  /**
   * Get Regex (public for debugging).
   */
  public function getRegex(): string {
    // @fixme Separator must change in different parts of URL.
    return $this->doGetRegex($this->pattern, '/');
  }

}

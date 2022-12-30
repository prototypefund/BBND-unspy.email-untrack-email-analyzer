<?php

declare(strict_types=1);

namespace Geeks4change\BbndAnalyzer\Analyzer\NewsletterServicesMatcher\UrlsMatcher\PerServiceUrlsMatcher;

use Geeks4change\BbndAnalyzer\Utility\UrlTool;
use Psr\Http\Message\UriInterface;

abstract class PerServiceUrlMatcherBase {

  use RegexTrait;

  protected string $name;

  protected string $pattern;

  protected string $tracking;

  protected string $type;

  protected function __construct(string $name, string $pattern, string $tracking, string $type) {
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

  /**
   * Get Regex (public for debugging).
   */
  public function getRegex(): string {
    // @fixme Separator must change in different parts of URL.
    return $this->doGetRegex($this->pattern, '/');
  }

  public function match(UriInterface $url): bool {
    $regex = $this->getRegex();
    foreach (UrlTool::getAllDomainAliases($url) as $aliasUrl) {
      $relevantUrl = UrlTool::relevantPart($aliasUrl);
      if (preg_match($regex, $relevantUrl)) {
        return TRUE;
      }
    }
    return FALSE;
  }

}

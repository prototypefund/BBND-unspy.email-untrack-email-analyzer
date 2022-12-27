<?php

declare(strict_types=1);

namespace Geeks4change\BbndAnalyzer\ServicesMatcher\HeadersInfo;
use Geeks4change\BbndAnalyzer\Globals;
use Geeks4change\BbndAnalyzer\ServicesMatcher\UrlsInfo\RegexTrait;
use ZBateson\MailMimeParser\Message;

class HeaderMatcher {

  use RegexTrait;

  protected string $name;

  protected string $pattern;

  public function __construct(string $name, string $pattern) {
    $this->name = $name;
    $this->pattern = $pattern;
  }

  public function matchHeaders(Message $message): bool {
    $header = $message->getHeader($this->name);
    if ($header) {
      foreach ($header->getParts() as $part) {
        $match = $this->match($part->getValue());
        if ($match) {
          return TRUE;
        }
      }
    }
    return FALSE;
  }

  /**
   * Match string (public for debugging).
   */
  public function match(string $value): bool {
    foreach ($this->getHeaderValues($value) as $value) {
      $regex = $this->getRegex();
      $match = boolval(preg_match($regex, $value));
      if ($match) {
        return TRUE;
      }
    }
    return FALSE;
  }

  protected function getHeaderValues(string $rawValue): \Iterator {
    // Care for domain names, like in ListUnsubscribe multi header.
    // Ignore port etc.
    if (preg_match_all('~http(?:s)://(.+?)/~u', $rawValue, $matches)) {
      // @todo Care for aliasing multiple domains once we need it.
      // By default it's group.match keys.
      $host = $matches[1][0];
      foreach (Globals::get()->getDomainAliasesResolver()->getAliases($host) as $alias) {
        yield str_replace($host, $alias, $rawValue);
      }
    }
    yield $rawValue;
  }

  /**
   * Get Regex (public for debugging).
   */
  public function getRegex(): string {
    // @todo Reconsider separator once we need it.
    return $this->doGetRegex($this->pattern, '');
  }

  public static function fromItem($value, string $key): self {
    // @fixme Make the schema more expressive.
    return new static($key, $value);
  }

  /**
   * @return string
   */
  public function getName(): string {
    return $this->name;
  }

}

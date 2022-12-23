<?php

declare(strict_types=1);

namespace Geeks4change\BbndAnalyzer\Pattern\HeaderPattern;
use Geeks4change\BbndAnalyzer\Pattern\RegexTrait;
use ZBateson\MailMimeParser\Message;

class HeaderPattern {

  use RegexTrait;

  protected string $name;

  protected string $pattern;

  public function __construct(string $name, string $pattern) {
    $this->name = $name;
    $this->pattern = $pattern;
  }

  public function match(Message $message): bool {
    $header = $message->getHeader($this->name);
    if ($header) {
      // Currently we don't need to support multi value headers.
      $value = $header->getValue();
      // @todo Reconsider separator once we need it.
      $regex = $this->getRegex($this->pattern, '');
      /** @noinspection PhpUnnecessaryLocalVariableInspection */
      $match = preg_match($regex, $value);
      return !empty($match);
    }
    return FALSE;
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

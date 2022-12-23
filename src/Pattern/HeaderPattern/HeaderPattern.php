<?php

declare(strict_types=1);

namespace Geeks4change\BbndAnalyzer\Pattern\HeaderPattern;
use ZBateson\MailMimeParser\Message;

class HeaderPattern {

  protected string $name;

  protected string $pattern;

  public function __construct(string $name, string $pattern) {
    $this->name = $name;
    $this->pattern = $pattern;
  }

  public function match(Message $message) {
    // @fixme
    $header = $message->getHeader($this->name);
    $value = $header->getValue();

    dump([$this->name => $header]);
  }

  public static function fromItem($value, string $key): self {
    // @fixme Make the schema more expressive.
    return new static($key, $value);
  }

}

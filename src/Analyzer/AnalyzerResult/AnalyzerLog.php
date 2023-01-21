<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult;

use loophp\collection\Collection;

/**
 * The analyzer log.
 *
 * ::getMessages(): All messages, for an administrator.
 * ::getRelevantTitles(): All message titles, for a searchable index.
 *
 * @api Will be serialized in persistent storage, any change needs a migration.
 */
final class AnalyzerLog {

  /**
   * @param string[] $messages
   */
  public function __construct(public readonly array $messages) {
  }

  /**
   * Get the title (up to \n) of all non-info entries.
   */
  public function getRelevantTitles(): array {
    return Collection::fromIterable($this->messages)
      ->filter(fn($line) => self::isRelevant($line))
      ->map(fn($line) => self::onlyFirstLine($line))
      ->all();
  }

  /**
   * Filter items >= Notice.
   * @see \Psr\Log\LogLevel
   */
  protected static function isRelevant(string $item): bool {
    return preg_match("~^(?:NOTICE |WARNING |ERROR |CRITICAL ALERT |EMERGENCY )~ui", $item);
  }

  protected static function onlyFirstLine(string $line): string {
    return preg_replace('~^.*$~u', '\\0', $line);
  }

}

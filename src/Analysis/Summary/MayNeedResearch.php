<?php

declare(strict_types=1);

namespace Geeks4change\BbndAnalyzer\Analysis\Summary;

/**
 * A logger for max-need-research messages.
 *
 * A singleton of this is retrieved by
 * @see \Geeks4change\BbndAnalyzer\Globals::getMayNeedResearch
 *
 * MayNeedResearch, child of
 * @see \Geeks4change\BbndAnalyzer\Analysis\Summary\AnalyzerResult
 *
 * @api Will be serialized in persistent storage, any change needs a migration.
 */
final class MayNeedResearch {

  /**
   * @var array<string>
   */
  protected array $items;

  public function add(string $item) {
    $this->items[] = $item;
  }

  /**
   * @return array<string>
   */
  public function getItems(): array {
    return $this->items;
  }

}

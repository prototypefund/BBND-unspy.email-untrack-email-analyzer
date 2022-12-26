<?php

declare(strict_types=1);

namespace Geeks4change\BbndAnalyzer\Analysis\Summary;

/**
 * MayNeedResearchSummary, child of
 * @see \Geeks4change\BbndAnalyzer\Analysis\Summary\Summary
 *
 * @api Will be serialized in persistent storage, any change needs a migration.
 */
final class MayNeedResearchSummary {

  /**
   * A list of "may need research" messages.
   *
   * @var array<string>
   */
  protected array $mayNeedResearchList;

  /**
   * @param string $mayNeedResearch
   */
  public function setMayNeedResearchList(string $mayNeedResearch): void {
    $this->mayNeedResearchList[] = $mayNeedResearch;
  }

  /**
   * @return array
   */
  public function getMayNeedResearchList(): array {
    return $this->mayNeedResearchList;
  }


}

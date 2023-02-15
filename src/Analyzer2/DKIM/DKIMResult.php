<?php

declare(strict_types=1);
namespace Geeks4change\UntrackEmailAnalyzer\Analyzer2\DKIM;

use Geeks4change\UntrackEmailAnalyzer\Analyzer2\DKIM\DKIMStatusEnum;

final class DKIMResult {

  /**
   * @param string[] $summaryLines
   */
  public function __construct(
    public readonly DKIMStatusEnum $status,
    public readonly array $summaryLines
  ) {}

}

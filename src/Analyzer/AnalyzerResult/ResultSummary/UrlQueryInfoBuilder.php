<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultSummary;

final class UrlQueryInfoBuilder {

  protected int $count = 0;

  public function __construct(
    protected array $analyticsKeys,
    protected array $otherKeys,
  ) {}

  public function add(): void {
    $this->count++;
  }

  public function freeze(): UrlQueryInfo {
    return new UrlQueryInfo($this->count, $this->analyticsKeys, $this->otherKeys);
  }

}

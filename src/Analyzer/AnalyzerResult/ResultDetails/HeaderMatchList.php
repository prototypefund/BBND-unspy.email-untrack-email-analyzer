<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\Arrayable\ToArrayInterface;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\Arrayable\ToArrayTrait;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\TestSummary\TestSummaryInterface;
use loophp\collection\Collection;

/**
 * HeaderSummaryPerService, child of
 *
 * @see \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\HeaderMatchListPerProvider
 *
 * @api Will be serialized in persistent storage, any change needs a migration.
 */
final class HeaderMatchList implements TestSummaryInterface, ToArrayInterface {

  use ToArrayTrait;

  /**
   * @param \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\HeaderMatch[] $matches
   */
  public function __construct(
    public readonly array $matches
  ) {}

  public function any(): bool {
    return Collection::fromIterable($this->matches)
      ->has(fn(HeaderMatch $hsm) => $hsm->isMatch);
  }

  public function all(): bool {
    return Collection::fromIterable($this->matches)
      ->every(fn(HeaderMatch $hsm) => $hsm->isMatch);
  }

  public function getTestSummary(): array {
    $countMatch = count(array_filter($this->matches, fn(HeaderMatch $hms) => $hms->isMatch));
    return $countMatch ? [
      '_countTotal' => count($this->matches),
      '_countMatch' => $countMatch,
    ] : [];
  }

}

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
 * @implements \IteratorAggregate<int, \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\HeaderMatch>
 */
final class HeaderMatchList implements TestSummaryInterface, ToArrayInterface, \IteratorAggregate {

  use ToArrayTrait;

  /**
   * @param \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\HeaderMatch[] $matches
   */
  public function __construct(
    public readonly array $matches
  ) {}

  public function getIterator(): \ArrayIterator {
    return new \ArrayIterator($this->matches);
  }

  public function any(): bool {
    return !Collection::fromIterable($this->matches)
      ->filter(fn(HeaderMatch $match) => $match->isMatch)
      ->isEmpty();
  }

  public function all(): bool {
    return Collection::fromIterable($this->matches)
      ->every(fn(HeaderMatch $match) => $match->isMatch);
  }

  public function getTestSummary(): array {
    $countMatch = count(array_filter($this->matches, fn(HeaderMatch $hms) => $hms->isMatch));
    return $countMatch ? [
      '_countTotal' => count($this->matches),
      '_countMatch' => $countMatch,
    ] : [];
  }

}

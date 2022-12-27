<?php

declare(strict_types=1);

namespace Geeks4change\BbndAnalyzer\Analysis\Summary;

/**
 * Result of links / images per service, child of
 * @see \Geeks4change\BbndAnalyzer\Analysis\Summary\UrlListMatchersResult
 */
final class UrlListPerServiceMatchesList implements \IteratorAggregate {

  /**
   * @var array<\Geeks4change\BbndAnalyzer\Analysis\Summary\UrlListPerServiceMatches>
   */
  protected array $perService;

  public function add(string $serviceName, UrlList $matchedExactly, UrlList $matchedByDomain) {
    $this->perService[] = new UrlListPerServiceMatches($serviceName, $matchedExactly, $matchedByDomain);
  }

  public function getIterator() {
    return new \ArrayIterator($this->perService);
  }

}

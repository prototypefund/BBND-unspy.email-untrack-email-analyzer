<?php

declare(strict_types=1);

namespace Geeks4change\BbndAnalyzer\Html;

/**
 * @implements \IteratorAggregate<string, \Psr\Http\Message\UriInterface>
 */
final class UriList implements \IteratorAggregate {

  protected array $uris;


  public function getIterator() {
    return new \ArrayIterator($this->uris);
  }

}

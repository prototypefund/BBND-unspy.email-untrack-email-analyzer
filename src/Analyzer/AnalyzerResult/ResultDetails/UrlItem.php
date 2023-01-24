<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails;

use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\UriInterface;

/**
 * Url item; child of
 *
 * @see \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\UrlList
 *
 * Effectively a string, but convenience methods may be added.
 *
 * Purposefully not coupled to any URL class.
 *
 * @api Will be serialized in persistent storage, any change needs a migration.
 */
final class UrlItem implements \Stringable {

  public function __construct(
    public readonly string $url
  ) {}

  public function getUrlObject(): UriInterface {
    return new Uri($this->url);
  }

  public function toString(): string {
    return $this->url;
  }

  public function __toString(): string {
    return $this->url;
  }

}
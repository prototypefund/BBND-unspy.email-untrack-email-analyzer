<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult;

use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\UriInterface;

/**
 * Url summary; child of
 *
 * @see \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\UrlList
 * @see \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\UrlListMatchersResult
 *
 * Effectively a string, but convenience methods may be added.
 *
 * Purposefully not coupled to any URL class.
 *
 * @api Will be serialized in persistent storage, any change needs a migration.
 */
final class UrlItem implements \Stringable {

  /**
   * Store a string, nothing more.
   * @var string
   */
  protected string $url;

  /**
   * @param string|\Stringable $url
   */
  public function __construct($url) {
    $this->url = strval($url);
  }

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
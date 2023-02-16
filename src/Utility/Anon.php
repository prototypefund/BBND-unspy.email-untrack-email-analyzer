<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Utility;

use GuzzleHttp\Psr7\Uri;

/**
 * Helpers to anonymize.
 */
final class Anon {

  // Use sth url safe.
  const string = 'xxxxx';

  public static function header(string $value): string {
    return self::string;
  }

  public static function text(string $value): string {
    return self::string;
  }

  public static function url(?string $urlString): ?string {
    if (!isset($urlString)) {
      return NULL;
    }
    $uri = new Uri($urlString);
    return $uri
      ->withPath(self::urlPath($uri->getPath()))
      ->withQuery(self::urlQuery($uri->getQuery()))
      ->withFragment(self::urlFragment($uri->getFragment()))
      ->__toString();
  }

  private static function urlPath(string $path): string {
    return implode('/', array_map(
      fn(string $name) => Anon::string,
      explode('/', $path)
    ));
  }

  private static function urlQuery(string $query): string {
    if (!$query) {
      return '';
    }
    parse_str($query, $queryArray);
    $anonArray = array_map(
      fn(mixed $value) => self::string,
      $queryArray
    );
    return http_build_query($anonArray);
  }

  private static function urlFragment(string $fragment): string {
    return $fragment ? self::string : '';
  }

}

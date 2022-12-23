<?php

declare(strict_types=1);

namespace Geeks4change\BbndAnalyzer\Utility;

use Geeks4change\BbndAnalyzer\DomainNames\DomainNameResolver;
use League\Uri\Uri;

class UriTool {

  public static function relevantPart(Uri $uri): string {
    return $uri->getHost() .
      $uri->getPath() .
      ($uri->getQuery() ? ('?' . $uri->getQuery()) : '');
  }

  /**
   * @param \League\Uri\Uri $uri
   *
   * @return array<\League\Uri\Uri>
   */
  public static function cNameAliases(Uri $uri): array {
    return array_map(
      fn(string $host) => $uri->withHost($host),
      DomainNameResolver::get()->resolve($uri->getHost())
    );
  }

  public static function isWebUrl(Uri $uri): bool {
    return $uri->getHost()
      && ($uri->getScheme() === 'http' || $uri->getScheme() === 'https');
  }

}

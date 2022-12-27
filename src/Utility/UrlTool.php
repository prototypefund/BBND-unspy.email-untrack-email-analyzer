<?php

declare(strict_types=1);

namespace Geeks4change\BbndAnalyzer\Utility;

use Geeks4change\BbndAnalyzer\Globals;
use Psr\Http\Message\UriInterface;

class UrlTool {

  public static function relevantPart(UriInterface $uri): string {
    return $uri->getHost() .
      $uri->getPath() .
      ($uri->getQuery() ? ('?' . $uri->getQuery()) : '');
  }

  /**
   * @return array<UriInterface>
   */
  public static function getAllDomainAliases(UriInterface $uri): array {
    return array_map(
      fn(string $host) => $uri->withHost($host),
      Globals::get()->getDomainAliasesResolver()->getAliases($uri->getHost())
    );
  }

  public static function isWebUrl(UriInterface $uri): bool {
    return $uri->getHost()
      && ($uri->getScheme() === 'http' || $uri->getScheme() === 'https');
  }

}
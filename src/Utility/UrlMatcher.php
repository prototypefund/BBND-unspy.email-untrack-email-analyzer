<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Utility;

use Geeks4change\UntrackEmailAnalyzer\CnameResolver;
use GuzzleHttp\Psr7\Uri;
use loophp\collection\Collection;

final class UrlMatcher {

  protected CnameResolver $cnameResolver;

  /**
   * @param string[] $domains
   * @param string $pathRegexes
   * @param string[] $queryPatterns
   */
  protected function __construct(
    public readonly array  $domains,
    public readonly string $pathRegexes,
    public readonly array  $queryPatterns,
  ) {
    $this->cnameResolver = new CnameResolver();
  }

  public function match(string $uriString): bool {
    $uri = new Uri($uriString);
    $domainMatch = $this->hostOrCnameMatchesAnyDomain($uri->getHost());
    $pathMatch = $this->pathMatchesPattern($uri->getPath());
    $queryMatch = $this->queryMatchesPattern($uri->getQuery());
    return $domainMatch && $pathMatch && $queryMatch;
  }

  protected function hostOrCnameMatchesAnyDomain(string $host): bool {
    return boolval(array_filter($this->domains, fn(string $domain) => $this->hostOrCnameMatchesSomeDomain($host, $domain)));
  }

  protected function hostOrCnameMatchesSomeDomain(string $host, string $domain): bool {
    $hostCnames = $this->cnameResolver->getCnames($host);
    return boolval(array_filter($hostCnames, fn(string $hostCname) => $this->hostMatchesSomeDomain($hostCname, $domain)));
  }

  protected function hostMatchesSomeDomain(string $host, string $domain): bool {
    if (str_starts_with($domain, '.')) {
      $isMatch = str_ends_with($host, $domain);
    }
    else {
      $isMatch = $host === $domain;
    }
    return $isMatch;
  }

  public function pathMatchesPattern(string $path): bool {
    return boolval(preg_match($this->pathRegexes, $path));
  }

  public function queryMatchesPattern(string $query): bool {
    parse_str($query, $queryArray);
    $matchesQueryKey = fn(?string $queryPattern, string|int $key) => isset($queryArray[$key])
      && (
        !$queryPattern ||
        preg_match($queryPattern, $queryArray[$key])
      );
    /** @noinspection PhpUnnecessaryLocalVariableInspection */
    $queryMatch = !$this->queryPatterns ||
      Collection::fromIterable($this->queryPatterns)
        ->every($matchesQueryKey);
    return $queryMatch;
  }

  protected static function createRegex(string $pattern, $separator): string {
    $quotedPattern = preg_quote($pattern, '~');
    $wildcard = $separator ? "[^{$separator}]+" : '.+';
    $regexPart = preg_replace('#\\\\{.*?\\\\}#u', $wildcard, $quotedPattern);
    /** @noinspection PhpUnnecessaryLocalVariableInspection */
    $regex = "~^{$regexPart}($|[?]|[&]|#)~u";
    return $regex;
  }

  /**
   * @param string $uriPattern
   *   A pattern containing {} as placeholder with contextual semantics.
   *     - If domain to match starts wit '.', any subdomain matches.
   *     - Path may contain {} placeholders that match any nonempty path
   *       segment (but not '/').
   *     - Query matches like this:
   *       - Must contain all specified keys.
   *       - If a pattern is given, it is matched against query string value,
   *         with {} matching any nonempty string.
   * @param array $domains
   *   If $domains is given, domain part of $uriPattern is ignored.
   *
   * @return \Geeks4change\UntrackEmailAnalyzer\Utility\UrlMatcher
   */
  public static function create(string $uriPattern, array $domains = []): UrlMatcher {
    $uri = new Uri($uriPattern);
    $domains = $uri->getHost() ? [$uri->getHost()] : $domains;
    $pathPattern = self::createRegex($uri->getPath(), '?');
    // Empty key (with or without '=') maps to empty string.
    parse_str($uri->getQuery(), $queryArray);
    $createQueryRegexes = fn(string $pattern) => $pattern ?
      // No '&' separator necessary, query is parsed beforehand.
      self::createRegex($pattern, '') : NULL;
    $queryPatterns = array_map($createQueryRegexes, $queryArray);
    return new self($domains, $pathPattern, $queryPatterns);
  }

}

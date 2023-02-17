<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Utility;

use Geeks4change\UntrackEmailAnalyzer\CnameResolver;
use Geeks4change\UntrackEmailAnalyzer\Globals;
use loophp\collection\Collection;

final class UrlMatcher {

  protected ?CnameResolver $cnameResolver;

  /**
   * @param string[] $domains
   * @param ?string $pathRegexes
   * @param string[] $queryPatterns
   */
  protected function __construct(
    public readonly array  $domains,
    public readonly ?string $pathRegexes,
    public readonly array  $queryPatterns,
  ) {
    $this->cnameResolver = Globals::get()->getCnameResolver();
  }

  /**
   * @param string $uriPattern
   *
   * @return string
   */
  public static function fixUriSchema(string $uriPattern): string {
    if (!str_starts_with($uriPattern, '/') && !str_contains($uriPattern, '//')) {
      $uriPattern = "//$uriPattern";
    }
    return $uriPattern;
  }

  public function match(string $uriString): bool {
    // Add empty schema if needed to ensure parsing.
    $uriString = self::fixUriSchema($uriString);
    [$host, $path, $query] = self::parseUrl($uriString);

    $domainMatch = $this->hostOrCnameMatchesAnyDomain($host);
    $pathMatch = $this->pathMatchesPattern($path);
    $queryMatch = $this->queryMatchesPattern($query);
    if (Globals::$isDebug) {
      dump(get_defined_vars() + [array_diff_key(get_object_vars($this), ['cnameResolver' => null])]);
    }
    return $domainMatch && $pathMatch && $queryMatch;
  }

  protected function hostOrCnameMatchesAnyDomain(string $host): bool {
    if (!$this->domains) {
      return true;
    }
    return boolval(array_filter($this->domains, fn(string $domain) => $this->hostOrCnameMatchesSomeDomain($host, $domain)));
  }

  protected function hostOrCnameMatchesSomeDomain(string $host, string $domain): bool {
    $hostCnames = $this->cnameResolver?->getCnameChain($host) ?? [$host];
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
    return !$this->pathRegexes || boolval(preg_match($this->pathRegexes, $path));
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
    // Add empty schema if needed to ensure parsing.
    // Paths must start with '/', otherwise it's host.
    $uriPattern = self::fixUriSchema($uriPattern);
    [$host, $path, $query] = self::parseUrl($uriPattern);

    if ($host) {
      if ($domains) {
        throw new \UnexpectedValueException('Can only give host or domains.');
      }
      $domains = [$host];
    }
    $pathPattern = self::createPathRegex($path);
    // Empty key (with or without '=') maps to empty string.
    parse_str($query, $queryArray);
    // No '&' separator necessary, query is parsed beforehand.
    // If no queryPattern, regex must be NULL.
    $createQueryRegexes = fn(string $queryPattern) => $queryPattern ?
      self::createQueryRegex($queryPattern) : NULL;
    $queryPatterns = array_map($createQueryRegexes, $queryArray);
    return new self($domains, $pathPattern, $queryPatterns);
  }

  protected static function createPathRegex(string $pattern): ?string {
    if (!$pattern) {
      return null;
    }
    $regexPart = self::createRegex($pattern, '/');
    if (!$regexPart) {
      $regexPart = '/?';
    }
    /** @noinspection PhpUnnecessaryLocalVariableInspection */
    $regex = "~^{$regexPart}[?]?[&]?[#]?$~u";
    return $regex;
  }

  protected static function createQueryRegex(string $pattern): string {
    $regexPart = self::createRegex($pattern, '');
    /** @noinspection PhpUnnecessaryLocalVariableInspection */
    $regex = "~^{$regexPart}$~u";
    return $regex;
  }

  protected static function createRegex(string $pattern, $separator): string {
    $quotedPattern = preg_quote($pattern, '~');
    $wildcard = $separator ? "[^{$separator}]+" : '.+';
    /** @noinspection PhpUnnecessaryLocalVariableInspection */
    // The curly braces are quoted now.
    $regexPart = preg_replace('#[\\\\][{].*?[\\\\][}]#u', $wildcard, $quotedPattern);
    return $regexPart;
  }

  public static function parseUrl(string $uriPattern): array {
    // Contrary to psr parsers, parse_url does not cough on e.g. '//.foo.bar'.
    $uriParts = parse_url($uriPattern);
    $host = $uriParts['host'] ?? '';
    $path = $uriParts['path'] ?? '';
    $query = $uriParts['query'] ?? '';
    return [$host, $path, $query];
  }

  /**
   * @param \Geeks4change\UntrackEmailAnalyzer\CnameResolver|null $cnameResolver
   */
  public function setCnameResolver(?CnameResolver $cnameResolver): void {
    $this->cnameResolver = $cnameResolver;
  }

}

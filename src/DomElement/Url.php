<?php

namespace Geeks4change\BbndAnalyzer\DomElement;

use Geeks4change\BbndAnalyzer\DomainNames\DomainNameResolver;

final class Url {

  protected string $originalUrl;

  protected string $originalRelevantUrlPart;

  protected array $effectiveHosts;

  protected string $pathAndQuery;

  public function __construct(string $originalUrl, string $originalRelevantUrlPart, array $effectiveHosts, string $pathAndQuery) {
    $this->originalUrl = $originalUrl;
    $this->originalRelevantUrlPart = $originalRelevantUrlPart;
    $this->effectiveHosts = $effectiveHosts;
    $this->pathAndQuery = $pathAndQuery;
  }


  public static function create(string $originalUrl, DomainNameResolver $domainNameResolver): ?self {
    $parts = parse_url($originalUrl);
    $scheme = $parts['scheme'];
    if (!($scheme === 'http' || $scheme === 'https')) {
      return NULL;
    }
    $host = $parts['host'] ?? '';
    $effectiveHosts = $domainNameResolver->resolve($host);
    $path = $parts['path'] ?? '';
    $query = $parts['query'] ?? '';
    $pathAndQuery = "{$path}" . ($query ? "?$query" : '');
    $originalRelevantUrlPart = "$host$pathAndQuery";
    return new self($originalUrl, $originalRelevantUrlPart, $effectiveHosts, $pathAndQuery);
  }

  /**
   * @return string
   */
  public function getOriginalUrl(): string {
    return $this->originalUrl;
  }

  /**
   * @return string
   */
  public function getOriginalRelevantUrlPart(): string {
    return $this->originalRelevantUrlPart;
  }

  /**
   * @return string
   */
  public function getPathAndQuery(): string {
    return $this->pathAndQuery;
  }

  /**
   * @return array<string>
   */
  public function getEffectiveHosts(): array {
    return $this->effectiveHosts;
  }

}
<?php

namespace Geeks4change\UntrackEmailAnalyzer\Matcher2;

use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\UrlItem;
use Geeks4change\UntrackEmailAnalyzer\DomainAliasesResolver;
use Geeks4change\UntrackEmailAnalyzer\Globals;
use GuzzleHttp\Psr7\Uri;
use loophp\collection\Collection;

abstract class MatcherBase {

  protected DomainAliasesResolver $domainAliasesResolver;

  public function __construct() {
    $this->domainAliasesResolver = Globals::get()->getDomainAliasesResolver();
  }

  public function anyHostInAngleBracketsMatchesAnyDomain(string $value): bool {
    return !Collection::fromIterable($this->extractAngleBrackets($value))
      ->map($this->extractHost(...))
      ->map($this->hostOrCnameMatchesAnyDomain(...),)
      ->filter()
      ->isEmpty();
  }

  public function anyValueInAngleBracketsMatchesAnyDomain(string $value): bool {
    return !Collection::fromIterable($this->extractAngleBrackets($value))
      ->map($this->hostOrCnameMatchesAnyDomain(...),)
      ->filter()
      ->isEmpty();
  }

  /**
   * @return list<string>
   */
  protected function extractAngleBrackets(string $value): array {
    preg_match_all('~<(.*?)>~u', $value, $matches);
    $extracted = $matches[1] ?? [];
    return $extracted;
  }

  protected function hostOrCnameMatchesAnyDomain(string $host): bool {
    $hostAndAllCnames = $this->domainAliasesResolver->getAliases($host);
    $isNotEmpty = !Collection::fromIterable($hostAndAllCnames)
      ->filter($this->hostMatchesAnyDomain(...))
      ->isEmpty();
    return $isNotEmpty;
  }

  protected function hostMatchesAnyDomain(string $host): bool {
    $isNotEmpty = !Collection::fromIterable($this->getDomains())
      ->filter(fn(string $domain) => $this->isSubdomainOf($host, $domain))
      ->isEmpty();
    return $isNotEmpty;
  }

  protected function isSubdomainOf(string $subdomain, string $domain): bool {
    $match = $subdomain === $domain
      || str_ends_with($subdomain, ".$domain");
    return $match;
  }

  /**
   * @param string $value
   *
   * @return string
   */
  public function extractHost(string $value): string {
    $uri = new Uri($value);
    $host = $uri->getHost();
    return $host;
  }

  public function extractPath(string $value): string {
    $uri = new Uri($value);
    $path = $uri->getPath();
    return ltrim($path, '/');
  }

  protected function matchUrl(UrlItem $urlItem, string $path): bool {
    $urlDomain = $this->extractHost($urlItem->url);
    $matchesAnyDomain = $this->hostOrCnameMatchesAnyDomain($urlDomain);
    $urlPath = $this->extractPath($urlItem->url);
    $matchesPath = $urlPath === $path;
    return $matchesAnyDomain && $matchesPath;
  }

}

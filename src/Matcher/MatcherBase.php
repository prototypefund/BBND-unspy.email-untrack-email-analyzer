<?php

namespace Geeks4change\UntrackEmailAnalyzer\Matcher;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\Result\Url\UrlItem;
use Geeks4change\UntrackEmailAnalyzer\CnameResolver;
use Geeks4change\UntrackEmailAnalyzer\Globals;
use GuzzleHttp\Psr7\Uri;
use loophp\collection\Collection;
use function Geeks4change\UntrackEmailAnalyzer\Matcher2\str_ends_with;

abstract class MatcherBase {

  protected CnameResolver $domainAliasesResolver;

  public function __construct() {
    $this->domainAliasesResolver = Globals::get()->getDomainAliasesResolver();
  }

  abstract protected function getDomains(): array;


  public function anyHostInAngleBracketsMatchesAnyDomain(string $value): bool {
    return !Collection::fromIterable($this->extractAngleBrackets($value))
      ->map($this->extractHost(...))
      ->map(fn(string $string) => $this->stringMatchesDomain($string),)
      ->filter()
      ->isEmpty();
  }

  public function anyValueInAngleBracketsMatchesAnyDomain(string $value): bool {
    return !Collection::fromIterable($this->extractAngleBrackets($value))
      ->map(fn(string $string) => $this->stringMatchesDomain($string),)
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

  protected function urlMatchesDomain(UrlItem $urlItem, array $domains = NULL): bool {
    return $this->stringMatchesDomain($this->extractHost($urlItem->url), $domains);
  }

  protected function stringMatchesDomain(string $host, array $domains = NULL): bool {
    $hostAndAllCnames = $this->domainAliasesResolver->getCnames($host);
    $isNotEmpty = !Collection::fromIterable($hostAndAllCnames)
      ->filter(fn(string $host) => $this->hosDomainWithoutCnames($host, $domains ?? $this->getDomains()))
      ->isEmpty();
    return $isNotEmpty;
  }

  private function hosDomainWithoutCnames(string $host, array $domains): bool {
    $isNotEmpty = !Collection::fromIterable($domains)
      ->filter(fn(string $domain) => $this->isSubdomainOf($host, $domain))
      ->isEmpty();
    return $isNotEmpty;
  }

  private function isSubdomainOf(string $subdomain, string $domain): bool {
    $match = $subdomain === $domain
      || str_ends_with($subdomain, ".$domain");
    return $match;
  }

  private function extractHost(string $value): string {
    $uri = new Uri($value);
    $host = $uri->getHost();
    return $host;
  }

  private function extractPath(string $value, bool $withQuery = FALSE): string {
    $uri = new Uri($value);
    $path = $uri->getPath();
    if ($withQuery) {
      $path .= '?' . $uri->getQuery();
    }
    return ltrim($path, '/');
  }

  protected function urlMatchPath(UrlItem $urlItem, string $pathPattern, bool $withQuery = FALSE): bool {
    $urlDomain = $this->extractHost($urlItem->url);
    $matchesAnyDomain = $this->stringMatchesDomain($urlDomain);
    $urlPath = $this->extractPath($urlItem->url, $withQuery);
    $matchesPath = preg_match($pathPattern, $urlPath);
    return $matchesAnyDomain && $matchesPath;
  }

}

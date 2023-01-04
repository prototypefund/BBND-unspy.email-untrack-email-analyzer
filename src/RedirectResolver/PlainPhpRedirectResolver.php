<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\RedirectResolver;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\UrlList;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\UrlRedirectInfo;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\UrlRedirectInfoList;

final class PlainPhpRedirectResolver {

  public function resolveRedirects(UrlList $urlList): UrlRedirectInfoList {
    $urlRedirectInfoList = new UrlRedirectInfoList();
    foreach ($urlList as $urlWrapper) {
      $url = $urlWrapper->toString();
      $redirectInfo = $this->resolveRedirect($url);
      if ($redirectInfo->hasRedirect()) {
        $urlRedirectInfoList->add($redirectInfo);
      }
    }
    return $urlRedirectInfoList;
  }

  /**
   * Resolve a redirect chain via plain PHP.
   *
   * Superseded by async resolver.
   *
   * Successor of a plain guzzle redirect resolver, which turned out to be bad
   * at the following.
   *
   * Get redirect info **without** actually requesting the target. Why?
   * - Some sites like LinkedIn serve invalid 999 status codes against crawling,
   *   and it is not so easy to work around that.
   * - Performing a full GET on an unsubscribe url often actually performs an
   *   unsubscribe. While against the spec, this is what we got in the wild.
   */
  public function resolveRedirect(string $url, $hops = 5): ?UrlRedirectInfo {
    // Note that stream_context_create and passing the context has different
    // result, as it breaks default crypto context.
    stream_context_set_default(
      [
        'http' => [
          'method' => 'HEAD',
          'timeout' => 6,
        ]
      ]
    );
    $redirectUrls = [];
    $currentUrl = $url;
    do {
      $headers = get_headers($currentUrl, TRUE);
      if ($headers) {
        $headers = self::normalizeHeaders($headers);
        [, $statusCode] = explode(' ', $headers[0]);
        $isRedirect = substr($statusCode, 0, 1) === '3';
        $currentUrl = $isRedirect ? $this->extractLocation($headers) : NULL;
      }
      $isValidRedirect = !empty($isRedirect) && !empty($currentUrl);
      if ($isValidRedirect) {
        $redirectUrls[] = $currentUrl;
      }
    } while ($isValidRedirect);

    return new UrlRedirectInfo($url, ...$redirectUrls);
  }

  protected static function normalizeHeaders(array $headers): array {
    $keys = array_keys($headers);
    $normalizedKeys = array_map('strtolower', $keys);
    return array_combine($normalizedKeys, $headers);
  }

  public function extractLocation($headers): ?string {
    // Either string or array of strings.
    // Mailchimp does freaky things like this:
    // 'location' => ['http://info@voeoe.de', 'https://voeoe.de/']
    $locations = (array) ($headers['location'] ?? []);
    foreach ($locations as $location) {
      if (is_string($location) && preg_match('~https?://~ui', $location)) {
        return $location;
      }
    }
    return NULL;
  }

}

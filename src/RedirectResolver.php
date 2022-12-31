<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\UrlRedirectInfo;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\RequestOptions;

final class RedirectResolver {

  /**
   * Resolve a redirect chain via plain PHP.
   *
   * Guzzle resolution broke helplessly on illegal 999 status codes, that are
   * used by sites like LinkedIn to nag scrapers.
   */
  public function resolveRedirect(string $url, $hops = 5): ?UrlRedirectInfo {
    // Note that stream_context_create and passing the context has different
    // result, as it breaks default crypto context.
    stream_context_set_default(
      [
        'http' => [
          'method' => 'HEAD',
          'timeout' => 3,
        ]
      ]
    );
    $redirectUrls = [];
    $currentUrl = $url;
    do {
      /** @noinspection PhpStrictTypeCheckingInspection */
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

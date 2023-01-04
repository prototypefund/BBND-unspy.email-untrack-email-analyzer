<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\Url;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\UrlList;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\UrlRedirectInfo;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\UrlRedirectInfoList;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Promise\Each;
use GuzzleHttp\Promise\Is;
use GuzzleHttp\Promise\Promise;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Promise\Utils;
use GuzzleHttp\RequestOptions;
use loophp\collection\Collection;
use loophp\collection\Contract\Operation\Unwrapable;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpClient\HttpClient;
use function GuzzleHttp\Promise\unwrap;

final class RedirectResolver {

  /**
   * Resolve a redirect chain via plain PHP.
   *
   * Get redirect info **without** actually requesting the target. Why?
   * - Some sites like LinkedIn serve invalid 999 status codes against crawling,
   *   and it is not so easy to work around that.
   * - Performing a full GET on an unsubscribe url often actually performs an
   *   unsubscribe. While against the spec, this is what we got in the wild.
   *
   * Guzzle turned out bad at that, so using symfony http client.
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

  /**
   * Resolve redirections.
   *
   * @fixme Clean this up:
   *   - Move everything to caller.
   *   - Throttle async so it won't hit rate limits.
   *   - Use $excludeUrlList
   */
  public function resolveRedirects(UrlList $urlList, UrlList $excludeUrlList): UrlRedirectInfoList {
    $urlRedirectInfoList = new UrlRedirectInfoList();
    foreach ($urlList as $urlWrapper) {
      $url = $urlWrapper->toString();
      if (!$excludeUrlList->contains($url)) {
        $redirectInfo = $this->resolveRedirect($url);
        if ($redirectInfo->hasRedirect()) {
          $urlRedirectInfoList->add($redirectInfo);
        }
      }
    }
    return $urlRedirectInfoList;
  }

  public function resolveRedirectsAsync(UrlList $urlList): UrlRedirectInfoList {
    $client = new Client(['allow_redirects' => FALSE, 'timeout' => 8]);
    $promises = new \ArrayIterator();
    $redirectMap = [];
    $addToPool = function (string $url) use (&$redirectMap, $promises, $client, &$addToPool) {
      if (!isset($promises[$url])) {
        dump("Requesting $url");
        $promises[$url] = $client->requestAsync('GET', $url)
          ->then(
            function (ResponseInterface $response) use (&$redirectMap, $url, &$addToPool) {
              if ($response->getStatusCode() >= 300 && $response->getStatusCode() < 400) {
                $redirectTarget = $response->getHeader('location')[0];
                dump("Resolve $url => $redirectTarget");
                $redirectMap[$url] = $redirectTarget;
                $addToPool($redirectTarget);
              }
              else {
                // We currently don't make a difference, if we get a 200, a 400,
                // or any exception, it's not a redirect.
                $redirectMap[$url] = NULL;
              }
            },
            function () use (&$redirectMap, $url) {
              $redirectMap[$url] = NULL;
            }
          );
      }
    };
    foreach ($urlList as $urlItem) {
      $addToPool($urlItem->toString());
    }

    $concurrencyLimit = 8;
    $settleWithRecursion = function ($promises) use ($concurrencyLimit, &$settleWithRecursion) {
    // @link https://github.com/guzzle/promises/issues/155
    return Utils::settle($promises, $concurrencyLimit)
      // @see recursion parameter of \GuzzleHttp\Promise\Utils::all
      // @link https://github.com/guzzle/promises/issues/46
      ->then(function ($results) use ($concurrencyLimit, &$promises, $settleWithRecursion) {
        foreach ($promises as $url => $promise) {
          if (Is::pending($promise)) {
            dump("Recurse $url");
            return $settleWithRecursion($promises, $concurrencyLimit);
          }
        }
        return $results;
      });
    };
    $all = $settleWithRecursion($promises);
    $all->wait();
    dump('RESOLVED');

    $urlRedirectInfoList = new UrlRedirectInfoList();
    foreach ($urlList as $urlItem) {
      $currentUrl = $urlItem->toString();
      $urlChain = [$currentUrl];
      while (isset($redirectMap[$currentUrl])) {
        $currentUrl = $redirectMap[$currentUrl];
        $urlChain[] = $currentUrl;
      }
      $urlRedirectInfo = new UrlRedirectInfo(...$urlChain);
      if ($urlRedirectInfo->hasRedirect()) {
        $urlRedirectInfoList->add($urlRedirectInfo);
      }
    }
    dump($urlRedirectInfoList);
    return $urlRedirectInfoList;
  }

}

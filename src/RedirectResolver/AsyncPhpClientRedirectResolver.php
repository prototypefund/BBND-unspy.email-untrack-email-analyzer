<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\RedirectResolver;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\UrlList;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\UrlRedirectInfo;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\UrlRedirectInfoList;
use Geeks4change\UntrackEmailAnalyzer\RedirectResolver\ClientDecorator\HttpClientDefaultHeaders;
use Geeks4change\UntrackEmailAnalyzer\RedirectResolver\ClientDecorator\SimpleThrottlingHttpClient;
use Symfony\Component\HttpClient\Exception\TimeoutException;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpClient\NoPrivateNetworkHttpClient;
use Symfony\Component\HttpClient\RetryableHttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * Redirect resolver, throttled, only one level.
 *
 * This has problems...
 * https://github.com/symfony/symfony/issues/48885
 *
 * Resolve redirects but prevent bans by
 * - throttling
 * - use GET
 * - add headers
 * Prevents accidental unsubscribing by
 * - Only fetching the first url, not the redirect.
 */
final class AsyncPhpClientRedirectResolver implements RedirectResolverInterface {

  protected int $throttleMilliSeconds = 2000;

  protected HttpClientInterface $client;

  public function __construct() {
    $this->client = HttpClient::create([
      'max_redirects' => 0,
      'timeout' => 5,
      'headers' => HttpClientDefaultHeaders::get(),
    ]);
    // Prevent SSRF attacks.
    $this->client = new NoPrivateNetworkHttpClient($this->client);
    // Retry on failuree. Defaults are fine.
    $this->client = new RetryableHttpClient($this->client, NULL, maxRetries: 2);
    // Throttle.
    $this->client = new SimpleThrottlingHttpClient($this->client, $this->throttleMilliSeconds);
  }

  public function resolveRedirects(UrlList $urlList): UrlRedirectInfoList {
    $urlRedirectInfoList = new UrlRedirectInfoList();
    // Span a try-catch around the complete lifetime of the async responses,
    // to not miss any exception.
    try {
      // Place all requests.
      $responses = [];
      // The "place-many-requests" foreach loop.
      foreach ($urlList as $urlItem) {
        $url = $urlItem->toString();
        if (!isset($responses[$url])) {
          $responses[$url] = $this->client->request('GET', $url);
        }
      }
      // The "work-down-all-responses" foreach loop.
      foreach ($responses as $url => $response) {
        // Span a try-catch around the response evaluation loop. So if a
        // response throws in that loop, processing continues for the next
        // response like we want it.
        // @fixme Assume an async response throws an exception **before**
        //   entering the foreach loop, or in the microsecond when looping.
        //   Then the exception is caught by the outer try-catch, dumping all
        //   remaining responses, which is not what we want.
        //   The problem is more general: Given multiple responses, to catch
        //   an exception for one response, the try clause is left and another
        //   response can throw an exception. Unresolvable.
        //   The above first approximation fortunately is only true in a world
        //   with true parallelism. Afaik we don't have true parallelism, bot
        //   fibers can only be switched (and the exception thrown) with an
        //   explicit switch or sleep or an i/o operation. Leveraging that
        //   knowledge, this for-loop is safe as long as we avoid any i/o
        //   outside the try-catch.
        //   But there's still the problem with the timespan **before** entering
        //   the work-down-all-responses foreach loop. While quite unprobable
        //   (bot more probable the more requests), it is perfectly possible
        //   that one of the responses throws timeout (or fwiw another exception),
        //   while a second one is placed, when the i/o operation of placing the
        //   second requests triggers the fiber event loop.
        //   How can we handle **that** situation?
        //   Anyway, it looks like event in this simplest use case of placing
        //   multiple events, catching exceptions needs a thorough knowledge
        //   of when and how the event loop can be triggered, and where the
        //   exception can be thrown.
        try {
          $redirectUrls = [];
          $isRedirectResponse = $response->getStatusCode() >= 300 && $response->getStatusCode() < 400;
          $redirectUrl = $isRedirectResponse ? $response->getHeaders(throw: FALSE)['location'][0] ?? NULL : NULL;;
          if (!empty($redirectUrl)) {
            $redirectUrls[] = $redirectUrl;
            $urlRedirectInfo = new UrlRedirectInfo($url, ...$redirectUrls);
            $urlRedirectInfoList->add($urlRedirectInfo);
            // In this version, intentionally do NOT follow redirections further than
            // one level.
          }
        } catch (TimeoutException $e) {
          // @todo Log.
        }
      }
      // Ensure destructor is in try-catch too. Otherwise, a response that is
      // header-complete, but not complete yet, might throw timeout after
      // leaving this scope, even if it's only a microsecond away from
      // destruction by leaving the function.
      unset($responses);
    } catch (TimeoutException $e) {
      // @todo Log.
    }
    return $urlRedirectInfoList;
  }

}

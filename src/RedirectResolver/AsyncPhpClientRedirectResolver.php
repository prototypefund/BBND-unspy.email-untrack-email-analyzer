<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\RedirectResolver;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\UrlList;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\UrlRedirectInfo;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\UrlRedirectInfoList;
use Geeks4change\UntrackEmailAnalyzer\RedirectResolver\ClientDecorator\HttpClientDefaultHeaders;
use Geeks4change\UntrackEmailAnalyzer\RedirectResolver\ClientDecorator\SimpleThrottlingHttpClient;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpClient\NoPrivateNetworkHttpClient;
use Symfony\Component\HttpClient\RetryableHttpClient;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

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

  protected float $timeoutSeconds = 5.0;

  protected HttpClientInterface $client;

  public function __construct() {
    $this->client = HttpClient::create([
      'max_redirects' => 0,
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
    $urlRedirectInfoList = UrlRedirectInfoList::builder();
    $responses = [];
    foreach ($urlList as $urlItem) {
      $url = $urlItem->toString();
      if (!isset($responses[$url])) {
        $responses[$url] = $this->client->request('GET', $url);
      }
    }
    // Pass in timeout only now to not get exceptions too early.
    foreach ($this->client->stream($responses, $this->timeoutSeconds) as $response => $chunk) {
      try {
        if ($chunk->isTimeout()) {
          $response->cancel();
        } elseif ($chunk->isFirst()) {
          $url = array_search($response, $responses, TRUE);
          // Headers arrived.
          $redirectUrls = [];
          $isRedirectResponse = $response->getStatusCode() >= 300 && $response->getStatusCode() < 400;
          /** @noinspection PhpUnhandledExceptionInspection */
          $redirectUrl = $isRedirectResponse ? $response->getHeaders(throw: FALSE)['location'][0] ?? NULL : NULL;;
          if (!empty($redirectUrl)) {
            $redirectUrls[] = $redirectUrl;
            $urlRedirectInfo = new UrlRedirectInfo($url, ...$redirectUrls);
            $urlRedirectInfoList->add($urlRedirectInfo);
            // In this version, intentionally do NOT follow redirections further than
            // one level.
          }
          // We don't need more.
          $response->cancel();
        } elseif ($chunk->isLast()) {
          // We will never get here.
        }
      } catch (TransportExceptionInterface $e) {
        // @todo Log.
      }
    }
    return $urlRedirectInfoList->freeze();
  }

}

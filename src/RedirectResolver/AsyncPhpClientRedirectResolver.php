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

  protected int $throttleMilliSeconds = 800;

  protected $client;

  public function __construct() {
    $this->client = HttpClient::create([
      'max_redirects' => 0,
      'timeout' => 3,
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
    // Place all requests.
    $responses = [];
    foreach ($urlList as $urlItem) {
      $url = $urlItem->toString();
      if (!isset($responses[$url])) {
        $responses[$url] = $this->client->request('GET', $url);
      }
    }
    // In this version, intentionally do NOT follow redirections further.
    $urlRedirectInfoList = new UrlRedirectInfoList();
    foreach ($responses as $url => $response) {
      $redirectUrls = [];
      try {
        $redirectUrl = $this->extractRedirect($response);
      } catch (TimeoutException $e) {
        // @fixme Log and ignore.
        // Rethrow for now.
        throw new RuntimeException('Problem resolving redirect', 0, $e);
      }
      if ($redirectUrl) {
        $redirectUrls[] = $redirectUrl;
        $urlRedirectInfo = new UrlRedirectInfo($url, ...$redirectUrls);
        $urlRedirectInfoList->add($urlRedirectInfo);
      }
    }
    return $urlRedirectInfoList;
  }

  protected function extractRedirect(ResponseInterface $response): ?string {
    if ($response->getStatusCode() >= 300 && $response->getStatusCode() < 400) {
      return $response->getHeaders(throw: false)['location'][0] ?? NULL;
    }
    else {
      return NULL;
    }
  }

}

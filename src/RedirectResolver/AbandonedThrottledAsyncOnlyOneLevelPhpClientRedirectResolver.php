<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\RedirectResolver;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\UrlList;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\UrlRedirectInfo;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\UrlRedirectInfoList;
use Geeks4change\UntrackEmailAnalyzer\RedirectResolver\ClientDecorator\SimpleThrottlingHttpClient;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpClient\NoPrivateNetworkHttpClient;
use Symfony\Component\HttpClient\RetryableHttpClient;

/**
 * Redirect resolver.
 *
 * ABANDONED, only for reference.
 * Symfony HttpClient has a nice async response that does not need promises.
 * Unfortunately it is deeply baked into it to not expose redirects.
 * @see \Symfony\Component\HttpClient\Response\CommonResponseTrait::checkStatusCode
 *
 * Resolve redirects but prevent bans by
 * - throttling
 * - use GET
 * - add headers
 */
final class AbandonedThrottledAsyncOnlyOneLevelPhpClientRedirectResolver implements RedirectResolverInterface {

  protected int $throttleMilliSeconds = 1000;

  protected $client;

  public function __construct() {
    // @fixme Add options.
    $this->client = HttpClient::create([
      'max_redirects' => 0,
      'timeout' => 8,
    ]);
    // Prevent SSRF attacks.
    $this->client = new NoPrivateNetworkHttpClient($this->client);
    // Retry on failuree. Defaults are fine.
    $this->client = new RetryableHttpClient($this->client);
    // Throttle.
    $this->client = new SimpleThrottlingHttpClient($this->client, $this->throttleMilliSeconds);
  }

  public function resolveRedirects(UrlList $urlList): UrlRedirectInfoList {
    // Place all requests.
    $responses = [];
    foreach ($urlList as $urlItem) {
      $url = $urlItem->toString();
      if (!isset($responses[$url])) {
        dump("Request: $url");
        $responses[$url] = $this->client->request('GET', $url);
      }
    }
    // In this version, intentionally do NOT follow redirections further.
    $urlRedirectInfoList = new UrlRedirectInfoList();
    foreach ($responses as $url => $response) {
      $redirectUrls = [];
      if ($redirectUrl = $this->extractRedirect($response)) {
        $redirectUrls[] = $$redirectUrl;
      }
      $urlRedirectInfo = new UrlRedirectInfo($url, ...$redirectUrls);
      $urlRedirectInfoList->add($urlRedirectInfo);
    }
    return $urlRedirectInfoList;
  }

  protected function extractRedirect(ResponseInterface $response): ?string {
    if ($response->getStatusCode() >= 300 && $response->getStatusCode() < 400) {
      return $response->getHeader('location')[0];
    }
    else {
      return NULL;
    }
  }

}

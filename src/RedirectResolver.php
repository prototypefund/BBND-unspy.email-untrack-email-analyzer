<?php

declare(strict_types=1);

namespace Geeks4change\BbndAnalyzer;

use Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult\UrlRedirectInfo;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\RequestOptions;

final class RedirectResolver {

  /**
   * Resolve a redirect chain via guzzle.
   *
   * This is FLAWED: If the final site returns an invalid status code (like
   * linkedin does when it detects a crawler), then the complete redirect chain
   * (including the redirect that gut us there) is lost.
   *
   * Can be used in Guzzle 6 and 7:
   * @see https://docs.guzzlephp.org/en/6.5/faq.html#how-can-i-track-redirected-requests
   * @see https://docs.guzzlephp.org/en/7.0/faq.html#how-can-i-track-redirected-requests
   */
  public function resolveRedirect(string $url): ?UrlRedirectInfo {
    // Only needed for debugging.
    $container = [];
    $history = Middleware::history($container);

    $stack = HandlerStack::create();
    // Add the history middleware to the handler stack.
    $stack->push($history);

    $client = new Client([
      RequestOptions::ALLOW_REDIRECTS => [
        'max'             => 10,
        'strict'          => false,
        'referer'         => true,
        'track_redirects' => true,
      ],
      RequestOptions::HEADERS => [
        'User-Agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Safari/537.36'
      ],
      // RequestOptions::DEBUG => TRUE,
      // 'handler' => $stack,
    ]);
    print "$url\n";
    // Sites like linkedin give illegal 999 status code when they detect a
    // crawler.
    try {
      $response = $client->request('GET', $url);
    } catch (\Exception $e) {}

    if (isset($response)) {
      $redirectUrls = $response->getHeader('X-Guzzle-Redirect-History');
    }
    else {
      $redirectUrls = [];
      //dump($container);
    }

    return new UrlRedirectInfo($url, ...$redirectUrls);
  }

}

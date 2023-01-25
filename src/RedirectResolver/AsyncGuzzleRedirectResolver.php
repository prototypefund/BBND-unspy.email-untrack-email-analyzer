<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\RedirectResolver;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\UrlList;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\UrlRedirectInfo;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\UrlRedirectInfoList;
use Geeks4change\UntrackEmailAnalyzer\RedirectResolver\ClientDecorator\HttpClientDefaultHeaders;
use Geeks4change\UntrackEmailAnalyzer\RedirectResolver\ClientDecorator\SimpleThrottlingGuzzleClientDecorator;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Promise\Is;
use GuzzleHttp\Promise\Utils;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;

/**
 * Async guzzle redirect resolver.
 */
final class AsyncGuzzleRedirectResolver implements RedirectResolverInterface {

  protected ClientInterface $client;

  protected bool $followMoreRedirects = FALSE;

  protected int $throttleInMilliseconds = 2000;

  public function __construct() {
    $this->client = new Client([
      RequestOptions::ALLOW_REDIRECTS => FALSE,
      RequestOptions::TIMEOUT => 8,
      RequestOptions::HEADERS => HttpClientDefaultHeaders::get(),
    ]);
    $this->client = new SimpleThrottlingGuzzleClientDecorator($this->client, $this->throttleInMilliseconds);
  }

  public function resolveRedirects(UrlList $urlList): UrlRedirectInfoList {
    $promises = new \ArrayIterator();
    $redirectMap = [];
    $addToPool = function (string $url) use (&$redirectMap, $promises, &$addToPool) {
      if (!isset($promises[$url])) {
        //dump("Requesting $url");
        $promises[$url] = $this->client->requestAsync('GET', $url)
          ->then(
            function (ResponseInterface $response) use (&$redirectMap, $url, &$addToPool) {
              if ($response->getStatusCode() >= 300 && $response->getStatusCode() < 400) {
                $redirectTarget = $response->getHeader('location')[0];
                //dump("Found redirect: $url => $redirectTarget");
                $redirectMap[$url] = $redirectTarget;
                if ($this->followMoreRedirects) {
                  $addToPool($redirectTarget);
                }
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
            //dump("Recurse $url");
            return $settleWithRecursion($promises, $concurrencyLimit);
          }
        }
        return $results;
      });
    };
    $all = $settleWithRecursion($promises);
    $all->wait();
    //dump('RESOLVED');
    //dump($redirectMap);

    $urlRedirectInfoList = new UrlRedirectInfoList();
    foreach ($urlList as $urlItem) {
      $currentUrl = $urlItem->toString();
      $urlChain = [$currentUrl];
      while (isset($redirectMap[$currentUrl])) {
        $currentUrl = $redirectMap[$currentUrl];
        $urlChain[] = $currentUrl;
      }
      $urlRedirectInfo = new UrlRedirectInfo(...$urlChain);
      if ($urlRedirectInfo->redirectUrls) {
        $urlRedirectInfoList->add($urlRedirectInfo);
      }
    }
    //dump($urlRedirectInfoList);
    return $urlRedirectInfoList;
  }

}

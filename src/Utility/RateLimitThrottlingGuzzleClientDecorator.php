<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Utility;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Client\ClientInterface as PsrClientInterface;
use Symfony\Component\RateLimiter\LimiterInterface;
use Symfony\Component\RateLimiter\RateLimiterFactory;
use Symfony\Component\RateLimiter\Storage\InMemoryStorage;

final class RateLimitThrottlingGuzzleClientDecorator extends GuzzleHttpDecoratorBase {

  protected LimiterInterface $limiter;

  public function __construct(ClientInterface|PsrClientInterface $decorated) {
    parent::__construct($decorated);
    // @link https://symfony.com/doc/current/rate_limiter.html#configuration
    // Token bucket has lowest interval 1 second.
    // https://github.com/symfony/symfony/issues/48871
    // Also https://github.com/symfony/symfony/issues/42627
    // FixedWindowLimiter coughs too.
    // https://github.com/symfony/symfony/issues/47676
    // Reserving tokens is not supported by SlidingWindowLimiter.
    $this->limiter = (new RateLimiterFactory([
        'id' => 'dontcare',
        // 'policy' => 'token_bucket',
        // 'limit' => 999,
        // 'rate' => ['interval' => '1 second', 'amount' => 1],
        'policy'  => 'fixed_window',
        'limit' => 1,
        'interval' => '500 milliseconds',
      ], new InMemoryStorage()))->create();
  }

  protected function getLimiter(string $uri): LimiterInterface {
    return $this->limiter;
  }

  public function requestAsync(string $method, $uri = '', array $options = []): PromiseInterface {
    $limiter = $this->getLimiter($uri);
    $limiter->reserve(1)->wait();
    return parent::requestAsync($method, $uri, $options);
  }

}

<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Utility;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Symfony\Contracts\HttpClient\ResponseStreamInterface;

final class SimpleThrottlingHttpClient implements HttpClientInterface {

  protected HttpClientInterface $decorated;

  protected int $waitMsecs;

  protected ?int $lastRequestTimestamp = NULL;

  public function __construct(HttpClientInterface $decorated, int $waitMsecs) {
    $this->decorated = $decorated;
    $this->waitMsecs = $waitMsecs;
  }

  public function request(string $method, string $url, array $options = []): ResponseInterface {
    $current = time();
    if ($this->lastRequestTimestamp) {
      $nextAllowedTimestamp = $this->lastRequestTimestamp + $this->waitMsecs;
      $toWait = $nextAllowedTimestamp - $current;
      if ($toWait > 0) {
        usleep(1000 * $toWait);
      }
    }
    $this->lastRequestTimestamp = $current;
    return $this->decorated->request($method, $url, $options);
  }

  public function stream(iterable|ResponseInterface $responses, float $timeout = NULL): ResponseStreamInterface {
    return $this->decorated->stream($responses, $timeout);
  }

  public function withOptions(array $options): static {
    return new static($this->decorated->withOptions($options), $this->waitMsecs);
  }

}

<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Utility;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Client\ClientInterface as PsrClientInterface;

final class SimpleThrottlingGuzzleClientDecorator extends GuzzleHttpDecoratorBase {

  protected int $waitMsecs;

  protected ?int $lastRequestTimestamp = NULL;

  public function __construct(ClientInterface|PsrClientInterface $decorated, int $waitMsecs) {
    parent::__construct($decorated);
    $this->waitMsecs = $waitMsecs;
  }

  public function requestAsync(string $method, $uri = '', array $options = []): PromiseInterface {
    $this->waitIfNeeded();
    return parent::requestAsync($method, $uri, $options);
  }

  /**
   * @return void
   */
  public function waitIfNeeded(): void {
    $current = time();
    if ($this->lastRequestTimestamp) {
      $nextAllowedTimestamp = $this->lastRequestTimestamp + $this->waitMsecs;
      $toWait = $nextAllowedTimestamp - $current;
      if ($toWait > 0) {
        usleep(1000 * $toWait);
      }
    }
    $this->lastRequestTimestamp = $current;
  }

}

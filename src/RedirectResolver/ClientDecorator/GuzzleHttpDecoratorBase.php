<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\RedirectResolver\ClientDecorator;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Client\ClientInterface as PsrClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

abstract class GuzzleHttpDecoratorBase implements ClientInterface, PsrClientInterface {

  /**
   * @var ClientInterface|PsrClientInterface
   */
  protected $decorated;

  /**
   * @param \GuzzleHttp\ClientInterface|\Psr\Http\Client\ClientInterface $decorated
   */
  public function __construct(ClientInterface|PsrClientInterface $decorated) {
    $this->decorated = $decorated;
  }

  public function sendAsync(RequestInterface $request, array $options = []): PromiseInterface {
    return $this->decorated->sendAsync($request, $options);
  }

  public function send(RequestInterface $request, array $options = []): ResponseInterface {
    return $this->decorated->send($request, $options);
  }

  public function sendRequest(RequestInterface $request): ResponseInterface {
    return $this->decorated->sendRequest($request);
  }

  public function requestAsync(string $method, $uri = '', array $options = []): PromiseInterface {
    return $this->decorated->requestAsync($method, $uri, $options);
  }

  public function request(string $method, $uri = '', array $options = []): ResponseInterface {
    return $this->decorated->request($method, $uri, $options);
  }

  public function getConfig(?string $option = NULL) {
    return $this->decorated->getConfig($option);
  }

}

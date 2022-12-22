<?php

namespace Geeks4change\BbndAnalyzer\DomElement;

final class Url {

  protected string $url;
  protected string $relevantUrl;

  protected string $host;

  protected string $path;

  protected string $query;

  /**
   * @param string $url
   */
  public function __construct(string $url) {
    $parts = parse_url($url);
    $this->url = $url;
    $this->host = $parts['host'] ?? '';
    $this->path = $parts['path'] ?? '';
    $this->query = $parts['query'] ?? '';
    $this->relevantUrl = "{$this->host}{$this->path}"
      . ($this->query ? "?$this->query" : '');
  }

  /**
   * @return string
   */
  public function getUrl(): string {
    return $this->url;
  }

  /**
   * @return string
   */
  public function getRelevantUrl(): string {
    return $this->relevantUrl;
  }

  /**
   * @return string
   */
  public function getHost(): string {
    return $this->host;
  }

  /**
   * @return string
   */
  public function getPath(): string {
    return $this->path;
  }

  /**
   * @return string
   */
  public function getQuery(): string {
    return $this->query;
  }

}
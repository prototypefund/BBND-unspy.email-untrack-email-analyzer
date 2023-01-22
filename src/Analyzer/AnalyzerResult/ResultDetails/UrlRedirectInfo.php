<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails;

final class UrlRedirectInfo implements \Stringable {

  protected string $originalUrl;

  /**
   * The resolution list.
   * @var array<string>
   */
  protected array $redirectUrls;

  /**
   * @param string $originalUrl
   * @param string ...$redirectUrls
   */
  public function __construct(string $originalUrl, string ...$redirectUrls) {
    $this->originalUrl = $originalUrl;
    $this->redirectUrls = $redirectUrls;
  }

  /**
   * @return string
   */
  public function getOriginalUrl(): string {
    return $this->originalUrl;
  }

  public function hasRedirect(): bool {
    return boolval($this->redirectUrls);
  }

  /**
   * @return array<string>
   */
  public function getRedirectUrls(): array {
    return $this->redirectUrls;
  }

  public function getFinalUrl(): string {
    return end($this->redirectUrls);
  }

  /**
   * @return array
   */
  public function getOriginalUrlAndRedirectUrls(): array {
    return array_merge([$this->originalUrl], $this->redirectUrls);
  }

  public function __toString(): string {
    return implode(' => ', $this->getOriginalUrlAndRedirectUrls());
  }

}

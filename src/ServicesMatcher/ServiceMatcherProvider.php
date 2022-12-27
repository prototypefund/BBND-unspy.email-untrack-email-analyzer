<?php

declare(strict_types=1);

namespace Geeks4change\BbndAnalyzer\ServicesMatcher;

use Geeks4change\BbndAnalyzer\ServicesMatcher\HeadersInfo\HeaderMatcher;
use Geeks4change\BbndAnalyzer\ServicesMatcher\UrlsInfo\DomainMatcher;
use Geeks4change\BbndAnalyzer\ServicesMatcher\UrlsInfo\ImageUrlMatcher;
use Geeks4change\BbndAnalyzer\ServicesMatcher\UrlsInfo\LinkUrlMatcher;
use Geeks4change\BbndAnalyzer\Utility\ArrayTool;

final class ServiceMatcherProvider {

  protected string $id;

  protected ?string $disconnectId;

  /**
   * @var array<\Geeks4change\BbndAnalyzer\ServicesMatcher\UrlsInfo\DomainMatcher>
   */
  protected array $domainMatchers;

  /**
   * @var array<\Geeks4change\BbndAnalyzer\ServicesMatcher\UrlsInfo\LinkUrlMatcher>
   */
  protected array $linkUrlMatchers;

  /**
   * @var array<\Geeks4change\BbndAnalyzer\ServicesMatcher\UrlsInfo\ImageUrlMatcher>
   */
  protected array $imageUrlMatchers;

  /**
   * @var array<\Geeks4change\BbndAnalyzer\ServicesMatcher\HeadersInfo\HeaderMatcher>
   */
  protected array $headerMatchers;

  /**
   * @param string $id
   * @param string|null $disconnectId
   * @param \Geeks4change\BbndAnalyzer\ServicesMatcher\UrlsInfo\DomainMatcher[] $domainMatchers
   * @param \Geeks4change\BbndAnalyzer\ServicesMatcher\UrlsInfo\LinkUrlMatcher[] $linkUrlMatchers
   * @param \Geeks4change\BbndAnalyzer\ServicesMatcher\UrlsInfo\ImageUrlMatcher[] $imageUrlMatchers
   * @param \Geeks4change\BbndAnalyzer\ServicesMatcher\HeadersInfo\HeaderMatcher[] $headerMatchers
   */
  public function __construct(string $id, ?string $disconnectId, array $domainMatchers, array $linkUrlMatchers, array $imageUrlMatchers, array $headerMatchers) {
    $this->id = $id;
    $this->disconnectId = $disconnectId;
    $this->domainMatchers = $domainMatchers;
    $this->linkUrlMatchers = $linkUrlMatchers;
    $this->imageUrlMatchers = $imageUrlMatchers;
    $this->headerMatchers = $headerMatchers;
  }


  public static function fromArray(string $id, array $array) {
    $disconnectId = $array['disconnect_id'] ?? NULL;

    $domainMatchers = ArrayTool::create($array['domains'] ?? [])
      ->map(fn($value, $key) => DomainMatcher::fromItem($value, $key));
    $linkUrlMatchers = ArrayTool::create($array['links'] ?? [])
      ->map(fn($value, $key) => LinkUrlMatcher::fromItem($value, $key));
    $imageUrlMatchers = ArrayTool::create($array['images'] ?? [])
      ->map(fn($value, $key) => ImageUrlMatcher::fromItem($value, $key));
    $headerMatchers = ArrayTool::create($array['headers']['patterns'] ?? [])
      ->map(fn($value, $key) => HeaderMatcher::fromItem($value, $key));

    return new self(
      $id,
      $disconnectId,
      $domainMatchers,
      $linkUrlMatchers,
      $imageUrlMatchers,
      $headerMatchers,
    );
  }

  /**
   * @return string
   */
  public function getName(): string {
    return $this->id;
  }

  /**
   * @return string|null
   */
  public function getDisconnectId(): ?string {
    return $this->disconnectId;
  }

  /**
   * @return array<\Geeks4change\BbndAnalyzer\ServicesMatcher\UrlsInfo\DomainMatcher>
   */
  public function getDomainMatchers(): array {
    return $this->domainMatchers;
  }

  /**
   * @return array
   */
  public function getLinkUrlMatchers(): array {
    return $this->linkUrlMatchers;
  }

  /**
   * @return array
   */
  public function getImageUrlMatchers(): array {
    return $this->imageUrlMatchers;
  }

  /**
   * @return array
   */
  public function getHeadersMatchers(): array {
    return $this->headerMatchers;
  }

}

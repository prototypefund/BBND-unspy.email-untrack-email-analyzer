<?php

declare(strict_types=1);

namespace Geeks4change\BbndAnalyzer\Analyzer\NewsletterServicesMatcher;

use Geeks4change\BbndAnalyzer\Analyzer\NewsletterServicesMatcher\HeadersMatcher\SingleHeaderMatcher;
use Geeks4change\BbndAnalyzer\Analyzer\NewsletterServicesMatcher\UrlsMatcher\PerServiceUrlsMatcher\PerServiceDomainMatcher;
use Geeks4change\BbndAnalyzer\Analyzer\NewsletterServicesMatcher\UrlsMatcher\PerServiceUrlsMatcher\PerServiceImageUrlMatcher;
use Geeks4change\BbndAnalyzer\Analyzer\NewsletterServicesMatcher\UrlsMatcher\PerServiceUrlsMatcher\PerServiceLinkUrlMatcher;
use Geeks4change\BbndAnalyzer\Utility\ArrayTool;

final class ServiceMatcherProvider {

  protected string $id;

  protected ?string $disconnectId;

  /**
   * @var array<\Geeks4change\BbndAnalyzer\Analyzer\NewsletterServicesMatcher\UrlsMatcher\PerServiceUrlsMatcher\PerServiceDomainMatcher>
   */
  protected array $domainMatchers;

  /**
   * @var array<\Geeks4change\BbndAnalyzer\Analyzer\NewsletterServicesMatcher\UrlsMatcher\PerServiceUrlsMatcher\PerServiceLinkUrlMatcher>
   */
  protected array $linkUrlMatchers;

  /**
   * @var array<\Geeks4change\BbndAnalyzer\Analyzer\NewsletterServicesMatcher\UrlsMatcher\PerServiceUrlsMatcher\PerServiceImageUrlMatcher>
   */
  protected array $imageUrlMatchers;

  /**
   * @var array<\Geeks4change\BbndAnalyzer\Analyzer\NewsletterServicesMatcher\HeadersMatcher\SingleHeaderMatcher>
   */
  protected array $headerMatchers;

  /**
   * @param string $id
   * @param string|null $disconnectId
   * @param \Geeks4change\BbndAnalyzer\Analyzer\NewsletterServicesMatcher\UrlsMatcher\PerServiceUrlsMatcher\PerServiceDomainMatcher[] $domainMatchers
   * @param \Geeks4change\BbndAnalyzer\Analyzer\NewsletterServicesMatcher\UrlsMatcher\PerServiceUrlsMatcher\PerServiceLinkUrlMatcher[] $linkUrlMatchers
   * @param \Geeks4change\BbndAnalyzer\Analyzer\NewsletterServicesMatcher\UrlsMatcher\PerServiceUrlsMatcher\PerServiceImageUrlMatcher[] $imageUrlMatchers
   * @param \Geeks4change\BbndAnalyzer\Analyzer\NewsletterServicesMatcher\HeadersMatcher\SingleHeaderMatcher[] $headerMatchers
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
      ->map(fn($value, $key) => PerServiceDomainMatcher::fromItem($value, $key));
    $linkUrlMatchers = ArrayTool::create($array['links'] ?? [])
      ->map(fn($value, $key) => PerServiceLinkUrlMatcher::fromItem($value, $key));
    $imageUrlMatchers = ArrayTool::create($array['images'] ?? [])
      ->map(fn($value, $key) => PerServiceImageUrlMatcher::fromItem($value, $key));
    $headerMatchers = ArrayTool::create($array['headers']['patterns'] ?? [])
      ->map(fn($value, $key) => SingleHeaderMatcher::fromItem($value, $key));

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
   * @return array<\Geeks4change\BbndAnalyzer\Analyzer\NewsletterServicesMatcher\UrlsMatcher\PerServiceUrlsMatcher\PerServiceDomainMatcher>
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

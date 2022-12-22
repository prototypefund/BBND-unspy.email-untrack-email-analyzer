<?php

declare(strict_types=1);

namespace Geeks4change\BbndAnalyzer\Pattern;

final class ToolPattern {

  protected string $id;

  protected ?string $disconnectId;

  /**
   * @var array<\Geeks4change\BbndAnalyzer\Pattern\DomainPattern>
   */
  protected array $domainPatterns;

  /**
   * @var array<\Geeks4change\BbndAnalyzer\Pattern\UrlPatternForLink>
   */
  protected array $linkPatterns;

  /**
   * @var array<\Geeks4change\BbndAnalyzer\Pattern\UrlPatternForImage>
   */
  protected array $imagePatterns;

  /**
   * @param string $id
   * @param string|null $disconnectId
   * @param \Geeks4change\BbndAnalyzer\Pattern\DomainPattern[] $domainPatterns
   * @param \Geeks4change\BbndAnalyzer\Pattern\UrlPatternForLink[] $linkPatterns
   * @param \Geeks4change\BbndAnalyzer\Pattern\UrlPatternForImage[] $imagePatterns
   */
  public function __construct(string $id, ?string $disconnectId, array $domainPatterns, array $linkPatterns, array $imagePatterns) {
    $this->id = $id;
    $this->disconnectId = $disconnectId;
    $this->domainPatterns = $domainPatterns;
    $this->linkPatterns = $linkPatterns;
    $this->imagePatterns = $imagePatterns;
  }

  public static function fromArray(string $id, array $array) {
    $disconnectId = $array['disconnect_id'] ?? NULL;
    $domainPatterns = array_map(
      fn($value, $key) => DomainPattern::fromItem($value, $key),
      $array['domains'] ?? [],
      array_keys($array['domains'] ?? []),
    );
    $linkPatterns = array_map(
      fn($value, $key) => UrlPatternForLink::fromItem($value, $key),
      $array['links'] ?? [],
      array_keys($array['link_patterns'] ?? []),
    );
    $imagePatterns = array_map(
      fn($value, $key) => UrlPatternForImage::fromItem($value, $key),
    $array['images'] ?? [],
      array_keys($array['image_patterns'] ?? []),
    );
    return new self(
      $id,
      $disconnectId,
      $domainPatterns,
      $linkPatterns,
      $imagePatterns,
    );
  }

  /**
   * @return string
   */
  public function getId(): string {
    return $this->id;
  }

  /**
   * @return string|null
   */
  public function getDisconnectId(): ?string {
    return $this->disconnectId;
  }

  /**
   * @return array<\Geeks4change\BbndAnalyzer\Pattern\DomainPattern>
   */
  public function getDomainPatterns(): array {
    return $this->domainPatterns;
  }

  /**
   * @return array
   */
  public function getLinkPatterns(): array {
    return $this->linkPatterns;
  }

  /**
   * @return array
   */
  public function getImagePatterns(): array {
    return $this->imagePatterns;
  }

}

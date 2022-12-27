<?php

declare(strict_types=1);

namespace Geeks4change\BbndAnalyzer\ServicesMatcher;

use Geeks4change\BbndAnalyzer\ServicesMatcher\HeadersInfo\HeadersPattern;
use Geeks4change\BbndAnalyzer\ServicesMatcher\UrlsInfo\DomainPattern;
use Geeks4change\BbndAnalyzer\ServicesMatcher\UrlsInfo\UrlPatternForImage;
use Geeks4change\BbndAnalyzer\ServicesMatcher\UrlsInfo\UrlPatternForLink;
use Geeks4change\BbndAnalyzer\Utility\ArrayTool;

final class ToolPattern {

  protected string $id;

  protected ?string $disconnectId;

  /**
   * @var array<\Geeks4change\BbndAnalyzer\ServicesMatcher\UrlsInfo\DomainPattern>
   */
  protected array $domainPatterns;

  /**
   * @var array<\Geeks4change\BbndAnalyzer\ServicesMatcher\UrlsInfo\UrlPatternForLink>
   */
  protected array $linkPatterns;

  /**
   * @var array<\Geeks4change\BbndAnalyzer\ServicesMatcher\UrlsInfo\UrlPatternForImage>
   */
  protected array $imagePatterns;

  /**
   * @var array<\Geeks4change\BbndAnalyzer\ServicesMatcher\HeadersInfo\HeadersPattern>
   */
  protected array $headerPatterns;

  /**
   * @param string $id
   * @param string|null $disconnectId
   * @param \Geeks4change\BbndAnalyzer\ServicesMatcher\UrlsInfo\DomainPattern[] $domainPatterns
   * @param \Geeks4change\BbndAnalyzer\ServicesMatcher\UrlsInfo\UrlPatternForLink[] $linkPatterns
   * @param \Geeks4change\BbndAnalyzer\ServicesMatcher\UrlsInfo\UrlPatternForImage[] $imagePatterns
   * @param \Geeks4change\BbndAnalyzer\ServicesMatcher\HeadersInfo\HeadersPattern[] $headerPatterns
   */
  public function __construct(string $id, ?string $disconnectId, array $domainPatterns, array $linkPatterns, array $imagePatterns, array $headerPatterns) {
    $this->id = $id;
    $this->disconnectId = $disconnectId;
    $this->domainPatterns = $domainPatterns;
    $this->linkPatterns = $linkPatterns;
    $this->imagePatterns = $imagePatterns;
    $this->headerPatterns = $headerPatterns;
  }


  public static function fromArray(string $id, array $array) {
    $disconnectId = $array['disconnect_id'] ?? NULL;

    $domainPatterns = ArrayTool::create($array['domains'] ?? [])
      ->map(fn($value, $key) => DomainPattern::fromItem($value, $key));
    $linkPatterns = ArrayTool::create($array['links'] ?? [])
      ->map(fn($value, $key) => UrlPatternForLink::fromItem($value, $key));
    $imagePatterns = ArrayTool::create($array['images'] ?? [])
      ->map(fn($value, $key) => UrlPatternForImage::fromItem($value, $key));
    $headerPatterns = ArrayTool::create($array['headers']['patterns'] ?? [])
      ->map(fn($value, $key) => HeadersPattern::fromItem($value, $key));

    return new self(
      $id,
      $disconnectId,
      $domainPatterns,
      $linkPatterns,
      $imagePatterns,
      $headerPatterns,
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
   * @return array<\Geeks4change\BbndAnalyzer\ServicesMatcher\UrlsInfo\DomainPattern>
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

  /**
   * @return array
   */
  public function getHeaderPatterns(): array {
    return $this->headerPatterns;
  }

}

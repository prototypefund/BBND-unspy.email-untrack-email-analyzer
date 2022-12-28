<?php

declare(strict_types=1);

namespace Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult;

use Geeks4change\BbndAnalyzer\TestHelpers\TestSummaryInterface;

/**
 * Summary of url lists (links, images) matching per service; child of
 *
 * @see \Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult\UrlListPerServiceMatchesList
 *
 * @api Will be serialized in persistent storage, any change needs a migration.
 */
final class UrlListPerServiceMatches implements TestSummaryInterface {

  protected string $serviceName;

  protected UrlList $urlsMatchedExactly;

  protected UrlList $urlsMatchedByDomain;

  /**
   * @param string $serviceName
   * @param \Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult\UrlList $matchedExactly
   * @param \Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult\UrlList $matchedByDomain
   */
  public function __construct(string $serviceName, UrlList $matchedExactly, UrlList $matchedByDomain) {
    $this->serviceName = $serviceName;
    $this->urlsMatchedExactly = $matchedExactly;
    $this->urlsMatchedByDomain = $matchedByDomain;
  }

  /**
   * @return string
   */
  public function getServiceName(): string {
    return $this->serviceName;
  }

  public function isNonEmpty(): bool {
    return $this->urlsMatchedExactly->count() > 0 || $this->urlsMatchedByDomain->count() > 0;
  }

  /**
   * @return \Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult\UrlList
   */
  public function getUrlsMatchedExactly(): UrlList {
    return $this->urlsMatchedExactly;
  }

  /**
   * @return \Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult\UrlList
   */
  public function getUrlsMatchedByDomain(): UrlList {
    return $this->urlsMatchedByDomain;
  }

  public function getTestSummary(): array {
    return [
      'urlsMatchedExactly' => $this->urlsMatchedExactly->getTestSummary(),
      'urlsMatchedByDomain' => $this->urlsMatchedByDomain->getTestSummary(),
    ];
  }

}

<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\Arrayable\ToArrayInterface;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\Arrayable\ToArrayTrait;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\TestSummary\TestSummaryInterface;

/**
 * Summary of url lists (links, images) matching per service; child of
 *
 * @see \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\UrlListPerServiceMatchesList
 *
 * @api Will be serialized in persistent storage, any change needs a migration.
 */
final class UrlListPerServiceMatches implements TestSummaryInterface, ToArrayInterface {

  use ToArrayTrait;

  /**
   * @param string $serviceName
   * @param \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\UrlList $urlsMatchedExactly
   * @param \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\UrlList $urlsMatchedByDomain
   * @param \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\UrlList $urlsNotMatchedList
   */
  public function __construct(
    public readonly string  $serviceName,
    public readonly UrlList $urlsMatchedExactly,
    public readonly UrlList $urlsMatchedByDomain,
    public readonly UrlList $urlsNotMatchedList
  ) {}

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
   * @return \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\UrlList
   */
  public function getUrlsMatchedExactly(): UrlList {
    return $this->urlsMatchedExactly;
  }

  /**
   * @return \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\UrlList
   */
  public function getUrlsMatchedByDomain(): UrlList {
    return $this->urlsMatchedByDomain;
  }

  /**
   * @return \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\UrlList
   */
  public function getUrlsNotMatchedList(): UrlList {
    return $this->urlsNotMatchedList;
  }

  public function getTestSummary(): array {
    return [
      'urlsMatchedExactly' => $this->urlsMatchedExactly->getTestSummary(),
      'urlsMatchedByDomain' => $this->urlsMatchedByDomain->getTestSummary(),
      'urlsNotMatchedList' => $this->urlsNotMatchedList->getTestSummary(),
    ];
  }

}
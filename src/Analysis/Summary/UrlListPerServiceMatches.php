<?php

declare(strict_types=1);

namespace Geeks4change\BbndAnalyzer\Analysis\Summary;

/**
 * Summary of url lists (links, images) matching per service; child of
 * @see \Geeks4change\BbndAnalyzer\Analysis\Summary\UrlListPerServiceMatchesList
 *
 * @api Will be serialized in persistent storage, any change needs a migration.
 */
final class UrlListPerServiceMatches {

  protected string $serviceName;

  protected UrlList $urlsMatchedExactly;

  protected UrlList $urlsMatchedByDomain;

  /**
   * @param string $serviceName
   * @param \Geeks4change\BbndAnalyzer\Analysis\Summary\UrlList $matchedExactly
   * @param \Geeks4change\BbndAnalyzer\Analysis\Summary\UrlList $matchedByDomain
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

  /**
   * @return \Geeks4change\BbndAnalyzer\Analysis\Summary\UrlList
   */
  public function getUrlsMatchedExactly(): UrlList {
    return $this->urlsMatchedExactly;
  }

  /**
   * @return \Geeks4change\BbndAnalyzer\Analysis\Summary\UrlList
   */
  public function getUrlsMatchedByDomain(): UrlList {
    return $this->urlsMatchedByDomain;
  }

}

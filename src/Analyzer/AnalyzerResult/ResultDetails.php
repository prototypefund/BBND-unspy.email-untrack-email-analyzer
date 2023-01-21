<?php

declare(strict_types=1);
namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\Arrayable\ToArrayInterface;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\Arrayable\ToArrayTrait;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\TestSummary\TestSummaryInterface;
use Geeks4change\UntrackEmailAnalyzer\Utility\PrintCollector;

/**
 * Analysis summary.
 *
 * @api Will be serialized in persistent storage, any change needs a migration.
 */
final class ResultDetails implements TestSummaryInterface, ToArrayInterface {

  use ToArrayTrait;

  public function __construct(
    public readonly DKIMResult                       $dkimResult,
    public readonly HeadersResult                    $headersResult,
    public readonly LinkAndImageUrlList              $allLinkAndImageUrlsList,
    public readonly LinkAndImageUrlListMatcherResult $linkAndImageUrlsMatcherResult,
    public readonly UrlList                          $pixelsList,
    public readonly UrlList                          $unsubscribeUrlList,
    public readonly LinkAndImageRedirectInfoList     $urlsRedirectInfoList,
    public readonly LinkAndImageUrlList              $urlsWithAnalyticsList,
    public readonly DomainAliasesList                $domainAliasesList,
  ) {}

  /**
   * @return \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\DKIMResult
   */
  public function getDkimResult(): DKIMResult {
    return $this->dkimResult;
  }

  /**
   * @return \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\HeadersResult
   */
  public function getHeadersResult(): HeadersResult {
    return $this->headersResult;
  }

  /**
   * @return \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\LinkAndImageUrlList
   */
  public function getAllLinkAndImageUrlsList(): LinkAndImageUrlList {
    return $this->allLinkAndImageUrlsList;
  }

  /**
   * @return \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\LinkAndImageUrlListMatcherResult
   */
  public function getLinkAndImageUrlsMatcherResult(): LinkAndImageUrlListMatcherResult {
    return $this->linkAndImageUrlsMatcherResult;
  }

  /**
   * @return \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\UrlList
   */
  public function getPixelsList(): UrlList {
    return $this->pixelsList;
  }

  /**
   * @return \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\UrlList
   */
  public function getUnsubscribeUrlList(): UrlList {
    return $this->unsubscribeUrlList;
  }

  /**
   * @return \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\LinkAndImageRedirectInfoList
   */
  public function getUrlsRedirectInfoList(): LinkAndImageRedirectInfoList {
    return $this->urlsRedirectInfoList;
  }

  /**
   * @return \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\LinkAndImageUrlList
   */
  public function getUrlsWithAnalyticsList(): LinkAndImageUrlList {
    return $this->urlsWithAnalyticsList;
  }

  /**
   * @return \Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\DomainAliasesList
   */
  public function getDomainAliasesList(): DomainAliasesList {
    return $this->domainAliasesList;
  }


  public function getTestSummary(): array {
    return [
      'headersResult' => $this->headersResult->getTestSummary(),
      'allLinkAndImageUrlsList' => $this->allLinkAndImageUrlsList->getTestSummary(),
      'linkAndImageUrlsResult' => $this->linkAndImageUrlsMatcherResult->getTestSummary(),
      'pixelsList' => $this->pixelsList->getTestSummary(),
      'urlsWithRedirectList' => $this->urlsRedirectInfoList->getTestSummary(),
      'urlsWithAnalyticsList' => $this->urlsWithAnalyticsList->getTestSummary(),
    ];
  }

  public function getAsPlainText(): string {
    $p = new PrintCollector();

    $p->add("");
    $p->add("");

    $p->add('# Analyzer result');
    if ($this->getAggregated()->getServiceName()) {
      $p->add("Recognized servide: {$this->getAggregated()->getServiceName()}");
    }
    if ($this->getAggregated()->getMatchLevel()) {
      $p->add("Confidence level: {$this->getAggregated()->getMatchLevel()}");
    }
    $p->add("");


    $p->add("# DKIM Result");
    $p->add("Signature verification confidence (red / yellow / green): " . $this->dkimResult->getStatus());
    $p->add("Details:");
    foreach ($this->dkimResult->getSummaryLines() as $dkimSummaryLine) {
      $p->add($dkimSummaryLine);
    }
    $p->add("");


    $p->add("# Headers result");
    foreach ($this->getHeadersResult() as $headersResult) {
      $p->add("## Match for service: {$headersResult->getServiceName()}");
      $p->add("Details:");
      foreach ($headersResult->getHeaderSingleResultList() as $headerSingleResultList) {
        $headerIsMatch = $headerSingleResultList->isMatch() ? 'MATCH' : 'nomatch';
        $p->add("- $headerIsMatch: {$headerSingleResultList->getHeaderName()}");
        // @todo Add match pattern.
        $p->add("  - Match pattern: xxx");
      }
    }
    $p->add("");


    $p->add("# All extracted links and images");
    foreach ([
               'Links' => $this->getAllLinkAndImageUrlsList()->getLinkUrlList(),
               'Images' => $this->getAllLinkAndImageUrlsList()->getImageUrlList(),
             ] as $urlListType => $urlList) {
      assert($urlList instanceof UrlList);
      $p->add("## {$urlList->count()} $urlListType URLs");
      $p->add("Details:");
      foreach ($urlList as $url) {
        $p->add('- ' . $url->toString());
      }
    }
    $p->add("");


    $p->add("# Service matcher result");
    foreach ([
               'Links' => $this->getLinkAndImageUrlsMatcherResult()
                 ->getLinkUrlsResult(),
               'Images' => $this->getLinkAndImageUrlsMatcherResult()
                 ->getImageUrlsResult(),
             ] as $urlsResultType => $urlsResult) {
      assert($urlsResult instanceof UrlListMatchersResult);
      foreach ($urlsResult->getPerServiceResultList() as $perServiceResultList) {
        foreach ([
                   'exactly' => $perServiceResultList->getUrlsMatchedExactly(),
                   'by domain' => $perServiceResultList->getUrlsMatchedByDomain(),
                   'not at all' => $perServiceResultList->getUrlsNotMatchedList(),
                 ] as $serviceMatchType => $servicePerTypeMatchUrlList) {
          assert($servicePerTypeMatchUrlList instanceof UrlList);
          // "## 42 links matched by domain for mailchimp"
          $p->add("## {$servicePerTypeMatchUrlList->count()} {$urlsResultType} matched {$serviceMatchType} for {$perServiceResultList->getServiceName()}");
          $p->add("Details:");
          foreach ($servicePerTypeMatchUrlList as $serviceMatchedUrl) {
            $p->add("- {$serviceMatchedUrl->toString()}");
            // @todo Add matching pattern.
            $p->add("  - Internal pattern: xxx");
          }
          $p->add("");
        }
        $p->add("");
      }
      $p->add("");
    }
    $p->add("");


    $p->add("# Recognized 1x1 pixels");
    foreach ($this->getPixelsList() as $pixelUrl) {
      $p->add("- {$pixelUrl->toString()}");
    }
    $p->add("");


    $p->add("# Recognized unsubscribe urls");
    foreach ($this->getUnsubscribeUrlList() as $unsubscribeUrl) {
      $p->add("- {$unsubscribeUrl->toString()}");
    }
    $p->add("");


    $p->add("# Recognized redirection urls");
    $p->add("(Without unsubscribe link)");
    foreach ([
               'Links' => $this->getUrlsRedirectInfoList()
                 ->getLinkRedirectInfoList(),
               'Images' => $this->getUrlsRedirectInfoList()
                 ->getImageRedirectInfoList(),
             ] as $urlRedirectionInfoType => $urlRedirectionInfoList) {
      assert($urlRedirectionInfoList instanceof UrlRedirectInfoList);
      $p->add("## $urlRedirectionInfoType with redirection");
      foreach ($urlRedirectionInfoList as $urlRedirectionInfo) {
        $p->add("- " . implode(' => ', $urlRedirectionInfo->getOriginalUrlAndRedirectUrls()));
      }
    }
    $p->add("");


    $p->add("# Recognized URLs with analytics");
    foreach ([
               'Links' => $this->getUrlsWithAnalyticsList()
                 ->getLinkUrlList(),
               'Images' => $this->getUrlsWithAnalyticsList()
                 ->getImageUrlList(),
             ] as $analyticsType => $analyticsUrlList) {
      assert($analyticsUrlList instanceof UrlList);
      $p->add("## $analyticsType with analytics");
      foreach ($analyticsUrlList as $analyticsUrl) {
        $p->add("- {$analyticsUrl->toString()}");
      }
    }
    $p->add("");


    $p->add("# Domains and aliases");
    foreach ($this->domainAliasesList as $domainAliases) {
      $p->add("- " . implode(' => ', $domainAliases->getDomainAndAliases()));
    }
    $p->add("");

    return $p->all();
  }

}

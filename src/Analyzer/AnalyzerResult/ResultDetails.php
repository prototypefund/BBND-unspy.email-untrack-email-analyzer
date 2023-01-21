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
    if ($this->aggregated->serviceName) {
      $p->add("Recognized servide: {$this->aggregated->serviceName}");
    }
    if ($this->aggregated->matchLevel) {
      $p->add("Confidence level: {$this->aggregated->matchLevel}");
    }
    $p->add("");


    $p->add("# DKIM Result");
    $p->add("Signature verification confidence (red / yellow / green): " . $this->dkimResult->status);
    $p->add("Details:");
    foreach ($this->dkimResult->summaryLines as $dkimSummaryLine) {
      $p->add($dkimSummaryLine);
    }
    $p->add("");


    $p->add("# Headers result");
    foreach ($this->headersResult as $headersResult) {
      $p->add("## Match for service: {$headersResult->serviceName}");
      $p->add("Details:");
      foreach ($headersResult->headerSingleResultList as $headerSingleResultList) {
        $headerIsMatch = $headerSingleResultList->isMatch() ? 'MATCH' : 'nomatch';
        $p->add("- $headerIsMatch: {$headerSingleResultList->headerName}");
        // @todo Add match pattern.
        $p->add("  - Match pattern: xxx");
      }
    }
    $p->add("");


    $p->add("# All extracted links and images");
    foreach ([
               'Links' => $this->allLinkAndImageUrlsList->linkUrlList,
               'Images' => $this->allLinkAndImageUrlsList->imageUrlList,
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
               'Links' => $this->linkAndImageUrlsMatcherResult
                 ->linkUrlsResult,
               'Images' => $this->linkAndImageUrlsMatcherResult
                 ->imageUrlsResult,
             ] as $urlsResultType => $urlsResult) {
      assert($urlsResult instanceof UrlListMatchersResult);
      foreach ($urlsResult->perServiceResultList as $perServiceResultList) {
        foreach ([
                   'exactly' => $perServiceResultList->urlsMatchedExactly,
                   'by domain' => $perServiceResultList->urlsMatchedByDomain,
                   'not at all' => $perServiceResultList->urlsNotMatchedList,
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
    foreach ($this->pixelsList as $pixelUrl) {
      $p->add("- {$pixelUrl->toString()}");
    }
    $p->add("");


    $p->add("# Recognized unsubscribe urls");
    foreach ($this->unsubscribeUrlList as $unsubscribeUrl) {
      $p->add("- {$unsubscribeUrl->toString()}");
    }
    $p->add("");


    $p->add("# Recognized redirection urls");
    $p->add("(Without unsubscribe link)");
    foreach ([
               'Links' => $this->urlsRedirectInfoList
                 ->linkRedirectInfoList,
               'Images' => $this->urlsRedirectInfoList
                 ->imageRedirectInfoList,
             ] as $urlRedirectionInfoType => $urlRedirectionInfoList) {
      assert($urlRedirectionInfoList instanceof UrlRedirectInfoList);
      $p->add("## $urlRedirectionInfoType with redirection");
      foreach ($urlRedirectionInfoList as $urlRedirectionInfo) {
        $p->add("- " . implode(' => ', $urlRedirectionInfo->originalUrlAndRedirectUrls));
      }
    }
    $p->add("");


    $p->add("# Recognized URLs with analytics");
    foreach ([
               'Links' => $this->urlsWithAnalyticsList
                 ->linkUrlList,
               'Images' => $this->urlsWithAnalyticsList
                 ->imageUrlList,
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
      $p->add("- " . implode(' => ', $domainAliases->domainAndAliases));
    }
    $p->add("");

    return $p->all();
  }

}

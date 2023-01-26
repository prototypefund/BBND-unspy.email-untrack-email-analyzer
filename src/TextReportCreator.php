<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\FullResult;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\PersistentResult;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\TypedUrlListPerProvider;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\UrlList;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\UrlRedirectInfoList;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\UrlTypeEnum;
use Geeks4change\UntrackEmailAnalyzer\Utility\PrintCollector;

final class TextReportCreator {

  public function createTextReport(FullResult|PersistentResult $result): string {
    $p = new PrintCollector();

    $p->add("");
    $p->add("");

    // @todo Add summary.

    $p->add("# DKIM Result");
    $p->add("Signature verification confidence (red / yellow / green): " . $result->details->dkimResult->status->value);
    $p->add("Details:");
    foreach ($result->details->dkimResult->summaryLines as $dkimSummaryLine) {
      $p->add($dkimSummaryLine);
    }
    $p->add("");


    $p->add("# Headers result");
    foreach ($result->details->headersResult as $providerId => $headersMatchList) {
      $p->add("## For service: {$providerId}");
      $p->add("Details:");
      foreach (['MATCH' => TRUE, 'No-Match' => FALSE] as $heading => $isMatchValue) {
        $p->add($heading);
        foreach ($headersMatchList as $headerMatch) {
          if ($headerMatch->isMatch === $isMatchValue) {
            $p->add("- {$headerMatch->headerName}: {$headerMatch->headerValue}");
          }
          // @todo Add match pattern.
          // $p->add("  - Match pattern: xxx");
        }
      }
    }
    $p->add("");


    $p->add("# All extracted links and images");
    foreach ([
               'Links' => $result->details->typedUrlList->typeLink,
               'Images' => $result->details->typedUrlList->typeImage,
             ] as $urlListType => $urlList) {
      assert($urlList instanceof UrlList);
      $p->add("## {$urlList->count()} $urlListType URLs");
      $p->add("Details:");
      foreach ($urlList as $url) {
        $p->add('- ' . $url->toString());
      }
    }
    $p->add("");


    // @todo...
    $p->add("# Service matcher result");
    foreach ([
               'exactly' => $result->details->exactMatches,
               'by domain' => $result->details->domainMatches,
             ] as $matchType => $typedUrlListPerProvider) {
      assert($typedUrlListPerProvider instanceof TypedUrlListPerProvider);
      foreach ($typedUrlListPerProvider as $providerId => $typedUrlList) {
        $p->add("");
        foreach ($typedUrlList as $urlType => $urlList) {
          assert($urlType instanceof UrlTypeEnum);
          // "## 42 image urls matched by domain for mailchimp"
          $p->add("## {$urlList->count()} {$urlType->value} urls matched {$matchType} for {$providerId}");
          $p->add("Details:");
          foreach ($urlList as $urlItem) {
            $p->add("- {$urlItem->toString()}");
            // @todo Add matching pattern.
            // $p->add("  - Internal pattern: xxx");
          }
        }
        $p->add("");
      }
    }
    $p->add("");


    $p->add("# Recognized 1x1 pixels");
    foreach ($result->details->pixelsList as $pixelUrl) {
      $p->add("- {$pixelUrl->toString()}");
    }
    $p->add("");


    $p->add("# Recognized unsubscribe urls");
    foreach ($result->details->unsubscribeUrlList as $unsubscribeUrl) {
      $p->add("- {$unsubscribeUrl->toString()}");
    }
    $p->add("");


    $p->add("# Recognized redirection urls");
    $p->add("(Without unsubscribe link)");
    foreach ([
               'Links' => $result->details->urlsRedirectInfoList
                 ->typeLink,
               'Images' => $result->details->urlsRedirectInfoList
                 ->typeImage,
             ] as $urlRedirectionInfoType => $urlRedirectionInfoList) {
      assert($urlRedirectionInfoList instanceof UrlRedirectInfoList);
      $p->add("## $urlRedirectionInfoType with redirection");
      foreach ($urlRedirectionInfoList as $urlRedirectionInfo) {
        $urls = [$urlRedirectionInfo->url, ...$urlRedirectionInfo->redirectUrls];
        $p->add("- " . implode(' => ', $urls));
      }
    }
    $p->add("");


    $p->add("# Recognized URLs with analytics");
    foreach ([
               'Links' => $result->details->urlsWithAnalyticsList
                 ->typeLink,
               'Images' => $result->details->urlsWithAnalyticsList
                 ->typeImage,
             ] as $analyticsType => $analyticsUrlList) {
      assert($analyticsUrlList instanceof UrlList);
      $p->add("## $analyticsType with analytics");
      foreach ($analyticsUrlList as $analyticsUrl) {
        $p->add("- {$analyticsUrl->toString()}");
      }
    }
    $p->add("");


    $p->add("# Domains and aliases");
    foreach ($result->details->cnameInfoList as $domainAliases) {
      $domainList = [$domainAliases->domain, ...$domainAliases->aliases];
      $p->add("- " . implode(' => ', $domainList));
    }
    $p->add("");

    return $p->all();
  }

}

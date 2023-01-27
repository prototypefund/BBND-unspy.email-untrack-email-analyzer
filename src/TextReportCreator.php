<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\FullResult;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\PersistentResult;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\TypedUrlListPerProvider;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\UrlList;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\UrlRedirectChainList;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\UrlTypeEnum;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultSummary\TypedUrlCountPerProvider;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultSummary\UrlQueryInfo;
use Geeks4change\UntrackEmailAnalyzer\Utility\PrintCollector;

final class TextReportCreator {

  public function createTextReport(FullResult|PersistentResult $result): string {
    $p = new PrintCollector();

    $date = (new \DateTime())->setTimestamp($result->messageInfo->timeStamp)
      ->format(\DateTimeInterface::ATOM);
    $p->add("# Analysis of '{$result->listInfo->emailLabel}<{$result->listInfo->emailAddress}>' from {$date}");
    $p->add("");

    $p->add("# Verdict");
    $p->add("Match level: {$result->verdict->matchLevel->value}");
    $serviceNameToPrint = $result->verdict->serviceName ?? '- Unknown -';
    $p->add("Service name: {$serviceNameToPrint}");
    $p->add("");

    $p->add("# Full analysis");

    // Nothing more in details than in summary.
    $p->add("# DKIM Result");
    $p->add("Signature verification confidence (red / yellow / green): " . $result->summary->dkimResult->status->value);
    $p->add("Details:");
    foreach ($result->summary->dkimResult->summaryLines as $dkimSummaryLine) {
      $p->add($dkimSummaryLine);
    }
    $p->add("");


    $p->add("# Headers result");
    foreach ($result->summary->headerMatches as $providerId => $headerSummaryMatches) {
      $headersMatchList = $result->details->headerMatches->get($providerId);
      $p->add("## For service {$providerId}");
      $headerMatchNames = implode(' / ', $headerSummaryMatches->matchNames);
      $p->add("Matching: $headerMatchNames");
      $headerNonMatchNames = implode(' / ', $headerSummaryMatches->nonMatchNames);
      $p->add("Not matching: $headerNonMatchNames");
      if ($result->details) {
        $p->add("## Details...");
        foreach (['MATCH' => TRUE, 'No-Match' => FALSE] as $heading => $isMatchValue) {
          $p->add($heading);
          foreach ($headersMatchList as $headerMatch) {
            if ($headerMatch->isMatch === $isMatchValue) {
              $p->add("- {$headerMatch->headerName}: {$headerMatch->headerValue}");
            }
          }
        }
      }
    }
    $p->add("");


    $p->add("# All extracted links and images");
    $p->add("- {$result->summary->typedUrlCount->typeLink} Links");
    $p->add("- {$result->summary->typedUrlCount->typeImage} Images");
    if ($result->details) {
      $p->add("## Details...");
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
    }
    $p->add("");


    // @todo...
    $p->add("# Service matcher result");
    foreach ([
               'exactly' => $result->summary->exactMatches,
               'by domain' => $result->summary->domainMatches,
             ] as $matchType => $typedUrlCountPerProvider) {
      assert($typedUrlCountPerProvider instanceof TypedUrlCountPerProvider);
      foreach ($typedUrlCountPerProvider as $providerId => $typedUrCount) {
        $p->add("## For provider {$providerId}");
        foreach ($typedUrCount as $urlType => $urlCount) {
          assert($urlType instanceof UrlTypeEnum);
          $p->add("- Found {$urlCount} {$urlType->value} matching {$matchType}");
          $p->add("- Found {$urlCount} {$urlType->value} matching {$matchType}");
        }
      }
    }
    if ($result->details) {
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
    }
    $p->add("");


    $p->add("# Recognized 1x1 pixels");
    $p->add("- Found {$result->summary->pixelsCount} pixels");
    if ($result->details) {
      $p->add("## Details...");
      foreach ($result->details->pixelsList as $pixelUrl) {
        $p->add("- {$pixelUrl->toString()}");
      }
    }
    $p->add("");


    // This is only relevant for details.
    if ($result->details) {
      $p->add("# Recognized unsubscribe urls");
      foreach ($result->details->unsubscribeUrlList as $unsubscribeUrl) {
        $p->add("- {$unsubscribeUrl->toString()}");
      }
      $p->add("");
    }


    $p->add("# Recognized redirection urls");
    $p->add("(Without unsubscribe link)");
    foreach ([
               'Links' => $result->summary->typedUrlRedirectCount
                 ->typeLink,
               'Images' => $result->summary->typedUrlRedirectCount
                 ->typeImage,
             ] as $urlRedirectionInfoType => $redirectCount) {
      $p->add("Found {$redirectCount} {$urlRedirectionInfoType} with redirect");
    }
    if ($result->details) {
      $p->add("## Details...");
      foreach ([
                 'Links' => $result->details->urlsRedirectInfoList
                   ->typeLink,
                 'Images' => $result->details->urlsRedirectInfoList
                   ->typeImage,
               ] as $urlRedirectionInfoType => $urlRedirectionInfoList) {
        assert($urlRedirectionInfoList instanceof UrlRedirectChainList);
        $p->add("## $urlRedirectionInfoType with redirection");
        foreach ($urlRedirectionInfoList as $urlRedirectionInfo) {
          $urls = [$urlRedirectionInfo->url, ...$urlRedirectionInfo->redirectUrls];
          $p->add("- " . implode(' => ', $urls));
        }
      }
    }
    $p->add("");


    $p->add("# Recognized URLs with analytics");
    foreach ([
               'Links' => $result->summary->typedAnalyticsKeyList
                 ->typeLink,
               'Images' => $result->summary->typedAnalyticsKeyList
                 ->typeImage,
             ] as $analyticsType => $urlQueryInfoList) {
      foreach ($urlQueryInfoList as $urlQueryInfo) {
        assert($urlQueryInfo instanceof UrlQueryInfo);
        $p->add("- {$urlQueryInfo->count} $analyticsType");
        $p->add("  - Analytics keys: " . implode(' ', $urlQueryInfo->analyticsKeys));
        $p->add("  - Other keys:     " . implode(' ', $urlQueryInfo->otherKeys));
      }
    }
    if ($result->details) {
      $p->add("## Details...");
      foreach ([
                 'Links' => $result->details->typedAnalyticsUrlList
                   ->typeLink,
                 'Images' => $result->details->typedAnalyticsUrlList
                   ->typeImage,
               ] as $analyticsType => $analyticsUrlList) {
        assert($analyticsUrlList instanceof UrlList);
        $p->add("## $analyticsType with analytics");
        foreach ($analyticsUrlList as $analyticsUrl) {
          $p->add("- {$analyticsUrl->toString()}");
        }
      }
    }
    $p->add("");


    // Nothing more in details than in summary.
    $p->add("# Domains and aliases");
    foreach ($result->summary->cnameChainList as $cnameInfo) {
      $domainList = [$cnameInfo->domain, ...$cnameInfo->aliases];
      $p->add("- " . implode(' => ', $domainList));
    }
    $p->add("");

    return $p->all();
  }

}

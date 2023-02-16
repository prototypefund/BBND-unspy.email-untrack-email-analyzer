<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Matcher;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\Result\FullResult;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\Result\FullResultWrapper;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\Result\Header\HeaderItemInfo;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\Result\Header\HeaderItemInfoBag;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\Result\Header\HeaderItemMatch;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\Result\ResultDetails;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\Result\Url\Match\ProviderMatch;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\Result\Url\UrlItemInfo;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\Result\Url\UrlItemInfoBag;

final class MatcherRestrictor {

  public static function restrictToMatcher(string $providerId, FullResultWrapper $wrapper): FullResultWrapper {
    // We need this for tests.
    return new FullResultWrapper(
      $wrapper->log,
      new FullResult(
        $wrapper->fullResult->listInfo,
        $wrapper->fullResult->messageInfo,
        $wrapper->fullResult->verdict,
        new ResultDetails(
          $wrapper->fullResult->details->dkimResult,
          new HeaderItemInfoBag(
            array_map(
              fn(HeaderItemInfo $info) => new HeaderItemInfo(
                $info->headerItem,
                array_values(array_filter(
                  $info->matches,
                  fn(HeaderItemMatch $match) => $match->providerId === $providerId,
                )),
              ),
              $wrapper->fullResult->details->headerItemInfoBag->infos,
            ),
          ),
          new UrlItemInfoBag(
            array_map(
              fn(UrlItemInfo $info) => new UrlItemInfo(
                $info->urlItem,
                $info->redirectInfo,
                $info->analyticsInfo,
                array_filter(
                  $info->matches,
                  fn(ProviderMatch $match) => $match->providerId === $providerId,
                ),
              ),
              $wrapper->fullResult->details->urlItemInfoBag->urlItemInfos,
            ),
          ),
          $wrapper->fullResult->details->cnameChainList,
        ),
      ),
    );
  }
}

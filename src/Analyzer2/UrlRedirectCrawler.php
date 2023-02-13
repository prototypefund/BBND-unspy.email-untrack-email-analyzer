<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer2;

use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\UrlItemInfo;
use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\UrlItemInfoBag;
use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\UrlItemInfoBagBuilder;
use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\UrlItemMatchType\RedirectInfo;
use Geeks4change\UntrackEmailAnalyzer\RedirectResolver\AgnosticRedirectResolverInterface;
use Geeks4change\UntrackEmailAnalyzer\RedirectResolver\AsyncGuzzleAgnosticRedirectResolver;
use loophp\collection\Collection;

final class UrlRedirectCrawler {

  public function __construct(
    protected ?AgnosticRedirectResolverInterface $redirectResolver = new AsyncGuzzleAgnosticRedirectResolver(),
  ) {}

  public function setRedirectResolver(?AgnosticRedirectResolverInterface $redirectResolver): void {
    $this->redirectResolver = $redirectResolver;
  }

  public function crawlRedirects(UrlItemInfoBag $urlItemInfoBag, Callable $allowCrawling): UrlItemInfoBag {
    $urlsTOCrawl = Collection::fromIterable($urlItemInfoBag->urlItemInfosByUrl)
      ->filter($allowCrawling)
      ->map(fn(UrlItemInfo $urlItemInfo) => $urlItemInfo->urlItem->url)
      ->all();

    $redirectMap = $this->redirectResolver?->resolveRedirects($urlsTOCrawl) ?? [];
    $builder = UrlItemInfoBagBuilder::fromUrlItemInfoBag($urlItemInfoBag);

    foreach ($urlItemInfoBag->urlItemInfosByUrl as $url => $info) {
      $wasCrawled = in_array($url, $urlsTOCrawl);
      $redirectUrl = $redirectMap[$url] ?? NULL;
      $redirectInfo = new RedirectInfo($redirectUrl, $wasCrawled);
      $builder->forUrlItem($urlItemInfoBag->forUrl($url)->urlItem)->setRedirectInfo($redirectInfo);
    }
    return $builder->freeze();
  }

}

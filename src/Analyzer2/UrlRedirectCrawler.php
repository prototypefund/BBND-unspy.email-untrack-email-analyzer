<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer2;

use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Result\Url\Redirect\RedirectInfo;
use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Result\Url\UrlItemInfo;
use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Result\Url\UrlItemInfoBag;
use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Result\Url\UrlItemInfoBagBuilder;
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
    $allUrlItemInfosByUrl = Collection::fromIterable($urlItemInfoBag->urlItemInfos)
      ->flip()
      ->map(fn(mixed $_, UrlItemInfo $urlItemInfo) => $urlItemInfo->urlItem->url)
      ->flip()
      ->all(FALSE);
    $urlItemsToCrawlByUrl = Collection::fromIterable($allUrlItemInfosByUrl)
      ->filter($allowCrawling)
      ->map(fn(UrlItemInfo $urlItemInfo) => $urlItemInfo->urlItem)
      ->all(FALSE);
    $allUrls = array_keys($allUrlItemInfosByUrl);
    $urlsToCrawl = array_keys($urlItemsToCrawlByUrl);

    $redirectMap = $this->redirectResolver?->resolveRedirects($urlsToCrawl) ?? [];
    $builder = UrlItemInfoBagBuilder::fromUrlItemInfoBag($urlItemInfoBag);

    foreach ($urlItemInfoBag->urlItemInfos as $info) {
      $url = $info->urlItem->url;
      $wasCrawled = in_array($url, $urlsToCrawl);
      if ($wasCrawled) {
        $redirectUrl = $redirectMap[$url] ?? NULL;
        $redirectInfo = new RedirectInfo($redirectUrl);
        $builder->forUrlItem($urlItemsToCrawlByUrl[$url])->setRedirectInfo($redirectInfo);
      }
    }
    return $builder->freeze();
  }

}

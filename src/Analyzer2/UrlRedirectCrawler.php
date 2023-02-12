<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer2;

use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\UrlItem;
use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\UrlItemBag;
use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\UrlRedirectBag;
use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\UrlRedirectBagBuilder;
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

  public function crawlRedirects(UrlItemBag $urlItemBag): UrlRedirectBag {
    $urls = Collection::fromIterable($urlItemBag->urlItems)
      ->map(fn(UrlItem $urlItem) => $urlItem->url)
      ->all();
    $redirectMap = $this->redirectResolver?->resolveRedirects($urls) ?? [];
    $urlRedirectBagBuilder = new UrlRedirectBagBuilder();
    foreach ($urlItemBag->urlItems as $urlItem) {
      $redirectUrl = $redirectMap[$urlItem->url] ?? NULL;
      $urlRedirectBagBuilder->addUrlRedirect($urlItem->type, $urlItem->url, $redirectUrl);
    }
    return $urlRedirectBagBuilder->freeze();
  }

}

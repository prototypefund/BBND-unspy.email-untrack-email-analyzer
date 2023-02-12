<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url;

final class UrlRedirectBag {

  /**
   * @var array<string, \Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\UrlRedirect> $urlRedirectsByUrl
   */
  public readonly array $urlRedirectsByUrl;

  /**
   * Redirects by URL.
   *
   * @param list<\Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\UrlRedirect> $urlRedirects
   */
  public function __construct(array $urlRedirects) {
    $urlRedirectsByUrl = [];
    foreach ($urlRedirects as $urlRedirect) {
      $urlRedirectsByUrl[$urlRedirect->url] = $urlRedirect;
    }
    $this->urlRedirectsByUrl = $urlRedirectsByUrl;
  }

  public function getRedirect(UrlItem $urlItem): ?UrlRedirect {
    // Not all urls have been crawled for redirects.
    return $this->urlRedirectsByUrl[$urlItem->url] ?? NULL;
  }

  public function getRedirectUrlItems(): UrlItemBag {
    $builder = new UrlItemBagBuilder();
    foreach ($this->urlRedirectsByUrl as $urlRedirect) {
      if ($urlRedirect->redirect) {
        $builder->addUrl($urlRedirect->type, $urlRedirect->redirect);
      }
    }
    return $builder->freeze();
  }

  public function getEffectiveUrlItems(): UrlItemBag {
    $builder = new UrlItemBagBuilder();
    foreach ($this->urlRedirectsByUrl as $urlRedirect) {
      if ($urlRedirect->redirect) {
        $builder->addUrl($urlRedirect->type, $urlRedirect->redirect);
      }
      else {
        $builder->addUrl($urlRedirect->type, $urlRedirect->url);
      }
    }
    return $builder->freeze();
  }

}

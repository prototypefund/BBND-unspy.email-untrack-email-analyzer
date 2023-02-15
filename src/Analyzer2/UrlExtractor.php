<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer2;

use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\UrlItemBag;
use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\UrlItemBagBuilder;
use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\UrlItemType;
use Symfony\Component\DomCrawler\Crawler;

final class UrlExtractor {

  public function extract(Crawler $crawler): UrlItemBag {
    $builder = new UrlItemBagBuilder();
    foreach ($crawler->filterXPath('//a[href]')->links() as $link) {
      // @todo Add link text.
      $builder->addUrl(UrlItemType::Link, $link->getUri(), $link->getNode()->textContent);
    }
    foreach ($crawler->filterXPath('//img[src]')->images() as $image) {
      $builder->addUrl(UrlItemType::Image, $image->getUri());
    }
    return $builder->freeze();
  }

}

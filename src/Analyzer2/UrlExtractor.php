<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer2;

use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\ImageUrl;
use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\LinkUrl;
use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\UrlItemBag;
use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\UrlItemBagBuilder;
use Symfony\Component\DomCrawler\Crawler;

final class UrlExtractor {

  public function extract(Crawler $crawler): UrlItemBag {
    $builder = new UrlItemBagBuilder();
    foreach ($crawler->filterXPath('//a[href]')->links() as $link) {
      // @todo Add link text.
      $builder->addUrlItem(new LinkUrl($link->getUri(), $link->getNode()->textContent));
    }
    foreach ($crawler->filterXPath('//img[src]')->images() as $image) {
      $builder->addUrlItem(new ImageUrl($image->getUri()));
    }
    return $builder->freeze();
  }

}

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
    foreach (UrlItemType::cases() as $urlType) {
      $urlNodes = $crawler->filterXPath($urlType->getXpath());
      foreach ($urlNodes as $urlNode) {
        /** @noinspection PhpPossiblePolymorphicInvocationInspection */
        $url = $urlNode->value;
        $builder->addUrl($urlType, $url);
      }
    }
    return $builder->freeze();
  }

}

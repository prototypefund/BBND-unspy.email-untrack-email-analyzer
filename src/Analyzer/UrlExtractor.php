<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\Result\Url\ImageUrl;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\Result\Url\LinkUrl;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\Result\Url\UrlItemBag;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\Result\Url\UrlItemBagBuilder;
use Symfony\Component\DomCrawler\Crawler;

final class UrlExtractor {

  public function extract(string $html): UrlItemBag {
    // Do we need all the bells and whistles of HtmlPageCrawler and underlying
    // DomCrawler? Not really, but seems to add some namespace and encoding
    // safeguards that can not be wrong.
    // Set URI (and implicitly basehref), so relative links do not throw.
    $crawler = new Crawler($html, 'https://example.com/');


    $builder = new UrlItemBagBuilder();
    foreach ($crawler->filterXPath('//a[@href]')->links() as $link) {
      // @todo Add link text.
      $builder->addUrlItem(new LinkUrl($link->getUri(), $link->getNode()->textContent));
    }
    foreach ($crawler->filterXPath('//img[@src]')->images() as $image) {
      $builder->addUrlItem(new ImageUrl($image->getUri()));
    }
    return $builder->freeze();
  }

}

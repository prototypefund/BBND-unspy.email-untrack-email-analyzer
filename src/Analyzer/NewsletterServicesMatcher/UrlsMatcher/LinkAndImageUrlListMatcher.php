<?php

declare(strict_types=1);

namespace Geeks4change\BbndAnalyzer\Analyzer\NewsletterServicesMatcher\UrlsMatcher;

use Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult\LinkAndImageUrlList;
use Geeks4change\BbndAnalyzer\Analyzer\AnalyzerResult\LinkAndImageUrlListMatcherResult;

final class LinkAndImageUrlListMatcher {

  public function generateLinkAndImageUrlListResults(LinkAndImageUrlList $linkAndImageUrlList): LinkAndImageUrlListMatcherResult {
    $linkUrlListResult = (new LinkUrlsMatcher())->generateUrlListResult($linkAndImageUrlList->getLinkUrlList());
    $imageUrlListResult = (new ImageUrlsMatcher())->generateUrlListResult($linkAndImageUrlList->getImageUrlList());
    return new LinkAndImageUrlListMatcherResult($linkUrlListResult, $imageUrlListResult);
  }

}

<?php

declare(strict_types=1);

namespace Geeks4change\BbndAnalyzer\ServicesMatching;

use Geeks4change\BbndAnalyzer\Analysis\Summary\LinkAndImageUrlList;
use Geeks4change\BbndAnalyzer\Analysis\Summary\LinkAndImageUrlListMatcherResult;

final class LinkAndImageUrlListMatcher {

  public function generateLinkAndImageUrlListResults(LinkAndImageUrlList $linkAndImageUrlList): LinkAndImageUrlListMatcherResult {
    $linkUrlListResult = (new LinkUrlsMatcher())->generateUrlListResult($linkAndImageUrlList->getLinkUrlList());
    $imageUrlListResult = (new ImageUrlsMatcher())->generateUrlListResult($linkAndImageUrlList->getImageUrlList());
    return new LinkAndImageUrlListMatcherResult($linkUrlListResult, $imageUrlListResult);
  }

}

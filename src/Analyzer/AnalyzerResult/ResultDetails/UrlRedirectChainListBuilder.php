<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails;

final class UrlRedirectChainListBuilder {

  /**
   * @var array<\Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\UrlRedirectChain>
   */
  protected array $urlRedirectInfoList = [];

  public function add(UrlRedirectChain $urlRedirectInfo): void {
    if (!$urlRedirectInfo->redirectUrls) {
      throw new \UnexpectedValueException();
    }
    $this->urlRedirectInfoList[$urlRedirectInfo->url] = $urlRedirectInfo;
  }

  public function freeze(): UrlRedirectChainList {
    return new UrlRedirectChainList($this->urlRedirectInfoList);
  }

}

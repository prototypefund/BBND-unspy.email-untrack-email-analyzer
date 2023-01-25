<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails;

final class UrlRedirectInfoListBuilder {

  /**
   * @var array<\Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\UrlRedirectInfo>
   */
  protected array $urlRedirectInfoList = [];

  public function add(UrlRedirectInfo $urlRedirectInfo): void {
    if (!$urlRedirectInfo->redirectUrls) {
      throw new \UnexpectedValueException();
    }
    $this->urlRedirectInfoList[$urlRedirectInfo->url] = $urlRedirectInfo;
  }

  public function freeze(): UrlRedirectInfoList {
    return new UrlRedirectInfoList($this->urlRedirectInfoList);
  }

}

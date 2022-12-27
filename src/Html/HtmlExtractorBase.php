<?php

declare(strict_types=1);

namespace Geeks4change\BbndAnalyzer\Html;

use Geeks4change\BbndAnalyzer\AnalyzerResult\UrlList;
use GuzzleHttp\Psr7\Uri;

/**
 * @internal
 */
abstract class HtmlExtractorBase {

  protected \DOMDocument $dom;

  /**
   * @param \DOMDocument $dom
   */
  public function __construct(\DOMDocument $dom) {
    $this->dom = $dom;
  }

  public function extract(string $html): UrlList {
    // Extract HTML part.
    $xpath = new \DOMXPath($this->dom);

    $linksList = $this->extractDomNodeList($xpath);

    $result = new UrlList();
    foreach (iterator_to_array($linksList) as $domNode) {
      $urlSpec = $this->extractUrlSpec($domNode);
      $uri = new Uri($urlSpec);
      if (in_array($uri->getScheme(), ['http', 'https'])) {
        $result->add($urlSpec);
      }
    }
    return $result;
  }


  abstract protected function extractDomNodeList(\DOMXPath $xpath): \DomNodeList;

  abstract protected function extractUrlSpec(\DomNode $domNode): string;

}

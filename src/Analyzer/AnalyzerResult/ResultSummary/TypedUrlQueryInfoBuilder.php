<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultSummary;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\UrlTypeEnum;
use loophp\collection\Collection;

final class TypedUrlQueryInfoBuilder {

  protected array $typeLink = [];
  protected array $typeImage = [];

  public function add(UrlTypeEnum $urlType, array $analyticsKeys, array $otherKeys) {
    // Use if, not match, to return references.
    if ($urlType === UrlTypeEnum::Link) {
      $subBuilderList =& $this->typeLink;
    }
    elseif ($urlType === UrlTypeEnum::Image) {
      $subBuilderList =& $this->typeImage;
    }
    $key = implode('+', $analyticsKeys) . '|' . implode('+', $otherKeys);
    $subBuilder = $subBuilderList[$key]
      ?? ($subBuilderList[$key] = new UrlQueryInfoBuilder($analyticsKeys, $otherKeys));
    // Add one on its count.
    $subBuilder->add();
  }

  public function freeze(): TypedUrlQueryInfo {
    $typeLink = Collection::fromIterable($this->typeLink)
      ->map(fn(UrlQueryInfoBuilder $builder) => $builder->freeze())
      // Throw away keys.
      ->all(TRUE);
    $typeImage = Collection::fromIterable($this->typeImage)
      ->map(fn(UrlQueryInfoBuilder $builder) => $builder->freeze())
      // Throw away keys.
      ->all(TRUE);
    return new TypedUrlQueryInfo($typeLink, $typeImage);
  }

}

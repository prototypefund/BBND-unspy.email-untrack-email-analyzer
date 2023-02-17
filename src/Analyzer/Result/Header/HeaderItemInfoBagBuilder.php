<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\Result\Header;

use loophp\collection\Collection;

final class HeaderItemInfoBagBuilder {

  /**
   * @param \SplObjectStorage<\Geeks4change\UntrackEmailAnalyzer\Analyzer\Result\Header\HeaderItem, \Geeks4change\UntrackEmailAnalyzer\Analyzer\Result\Header\HeaderItemInfoBuilder> $infos
   */
  protected function __construct(
    protected \SplObjectStorage $infos,
  ) {}

  public static function fromHeaderItemBag(HeaderItemBag $headerItemBag): self {
    // For convenience, index HeaderItemInfoBuilders by their HeaderItems.
    $infos = new \SplObjectStorage();
    $keys = Collection::fromIterable($headerItemBag->items);
    $values = $keys->map(
      fn(HeaderItem $item): HeaderItemInfoBuilder => new HeaderItemInfoBuilder($item)
    );
    // Combine to HeaderItem => HeaderItemInfoBuilder.

    $keys->zip($values)->unwrap()->pair()
      ->apply(fn(HeaderItemInfoBuilder $v, HeaderItem $k) => $infos->offsetSet($k, $v))
      ->squash();
    return new self($infos);
  }

  protected function getHeaderItemInfoBuilder(HeaderItem $headerItem): HeaderItemInfoBuilder {
    return $this->infos->offsetGet($headerItem) ?? throw new \UnexpectedValueException();
  }

  /**
   * @return HeaderItem
   */
  public function getHeaderItems(): array {
    // SplObjectStorage iterates over the key objects, sigh.
    return Collection::fromIterable($this->infos)->all();
  }

  public function addMatch(HeaderItem $headerItem, HeaderItemMatch $match) {
    $this->getHeaderItemInfoBuilder($headerItem)
      ->addMatch($match);
  }

  public function freeze(): HeaderItemInfoBag {
    $headerItemInfos = Collection::fromIterable($this->infos)
      ->map($this->getHeaderItemInfoBuilder(...))
      ->map(fn(HeaderItemInfoBuilder $builder) => $builder->freeze())
      // Only keep header items that have matches at all.
      ->filter(fn(HeaderItemInfo $info) => !empty($info->matches))
      ->all();
    return new HeaderItemInfoBag($headerItemInfos);
  }

}

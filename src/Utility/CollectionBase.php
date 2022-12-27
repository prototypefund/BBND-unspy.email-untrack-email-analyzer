<?php

declare(strict_types=1);

namespace Geeks4change\BbndAnalyzer\Utility;
use Closure;
use Doctrine\Common\Collections\Criteria;
use Generator;
use Iterator;
use loophp\collection\Collection;
use loophp\collection\Contract\Collection as CollectionInterface;
use loophp\collection\Contract\Operation;
use Psr\Cache\CacheItemPoolInterface;

class CollectionBase implements CollectionInterface {

  protected CollectionInterface $decorated;

  /**
   * @param \loophp\collection\Contract\Collection $decorated
   */
  protected function __construct(CollectionInterface $decorated) {
    $this->decorated = $decorated;
  }

  public function all(bool $normalize = TRUE): array {
    return $this->decorated->all($normalize);
  }

  public function append(...$items): CollectionInterface {
    return $this->decorated->append(...$items);
  }

  public function apply(callable ...$callbacks): CollectionInterface {
    return $this->decorated->apply(...$callbacks);
  }

  public function associate(?callable $callbackForKeys = NULL, ?callable $callbackForValues = NULL): CollectionInterface {
    return $this->decorated->associate($callbackForKeys, $callbackForValues);
  }

  public function asyncMap(callable $callback): CollectionInterface {
    return $this->decorated->asyncMap($callback);
  }

  public function asyncMapN(callable ...$callbacks): CollectionInterface {
    return $this->decorated->asyncMapN(...$callbacks);
  }

  public function cache(?CacheItemPoolInterface $cache = NULL): CollectionInterface {
    return $this->decorated->cache($cache);
  }

  public function chunk(int ...$sizes): CollectionInterface {
    return $this->decorated->chunk(...$sizes);
  }

  public function coalesce(): CollectionInterface {
    return $this->decorated->coalesce();
  }

  public function collapse(): CollectionInterface {
    return $this->decorated->collapse();
  }

  public function column($column): CollectionInterface {
    return $this->decorated->column($column);
  }

  public function combinate(?int $length = NULL): CollectionInterface {
    return $this->decorated->combinate($length);
  }

  public function combine(...$keys): CollectionInterface {
    return $this->decorated->combine(...$keys);
  }

  public function compact(...$values): CollectionInterface {
    return $this->decorated->compact(...$values);
  }

  public function contains(...$values): bool {
    return $this->decorated->contains(...$values);
  }

  public function count(): int {
    return $this->decorated->count();
  }

  public function current(int $index = 0, $default = NULL) {
    return $this->decorated->current($index, $default);
  }

  public function cycle(): CollectionInterface {
    return $this->decorated->cycle();
  }

  public function diff(...$values): CollectionInterface {
    return $this->decorated->diff(...$values);
  }

  public function diffKeys(...$keys): CollectionInterface {
    return $this->decorated->diffKeys(...$keys);
  }

  public function distinct(?callable $comparatorCallback = NULL, ?callable $accessorCallback = NULL): CollectionInterface {
    return $this->decorated->distinct($comparatorCallback, $accessorCallback);
  }

  public function drop(int $count): CollectionInterface {
    return $this->decorated->drop($count);
  }

  public function dropWhile(callable ...$callbacks): CollectionInterface {
    return $this->decorated->dropWhile(...$callbacks);
  }

  public function dump(string $name = '', int $size = 1, ?Closure $closure = NULL): CollectionInterface {
    return $this->decorated->dump($name, $size, $closure);
  }

  public function duplicate(?callable $comparatorCallback = NULL, ?callable $accessorCallback = NULL): CollectionInterface {
    return $this->decorated->duplicate($comparatorCallback, $accessorCallback);
  }

  public static function empty(): CollectionInterface {
    return new static(Collection::empty());
  }

  public function equals(CollectionInterface $other): bool {
    return $this->decorated->equals($other);
  }

  public function every(callable ...$callbacks): bool {
    return $this->decorated->every(...$callbacks);
  }

  public function explode(...$explodes): CollectionInterface {
    return $this->decorated->explode(...$explodes);
  }

  public function falsy(): bool {
    return $this->decorated->falsy();
  }

  public function filter(callable ...$callbacks): CollectionInterface {
    return $this->decorated->filter($callbacks);
  }

  public function find($default = NULL, callable ...$callbacks) {
    return $this->decorated->find($default, $callbacks);
  }

  public function first(): CollectionInterface {
    return $this->decorated->first();
  }

  public function flatMap(callable $callback): CollectionInterface {
    return $this->decorated->flatMap($callback);
  }

  public function flatten(int $depth = PHP_INT_MAX): CollectionInterface {
    return $this->decorated->flatten($depth);
  }

  public function flip(): CollectionInterface {
    return $this->decorated->flip();
  }

  public function foldLeft(callable $callback, $initial = NULL): CollectionInterface {
    return $this->decorated->foldLeft($callback, $initial);
  }

  public function foldLeft1(callable $callback): CollectionInterface {
    return $this->decorated->foldLeft1($callback);
  }

  public function foldRight(callable $callback, $initial = NULL): CollectionInterface {
    return $this->decorated->foldRight($callback, $initial);
  }

  public function foldRight1(callable $callback): CollectionInterface {
    return $this->decorated->foldRight1($callback);
  }

  public function forget(...$keys): CollectionInterface {
    return $this->decorated->forget(...$keys);
  }

  public function frequency(): CollectionInterface {
    return $this->decorated->frequency();
  }

  /**
   * @return static
   */
  #[\ReturnTypeWillChange]
  public static function fromCallable(callable $callable, iterable $parameters = []) {
    return new static(Collection::fromCallable($callable, $parameters));
  }

  /**
   * @return static
   */
  #[\ReturnTypeWillChange]
  public static function fromFile(string $filepath) {
    return new static(Collection::fromFile($filepath));
  }

  /**
   * @return static
   */
  #[\ReturnTypeWillChange]
  public static function fromGenerator(Generator $generator) {
    return new static(Collection::fromGenerator($generator));
  }

  /**
   * @return static
   */
  #[\ReturnTypeWillChange]
  public static function fromIterable(iterable $iterable) {
    return new static(Collection::fromIterable($iterable));
  }

  /**
   * @return static
   */
  #[\ReturnTypeWillChange]
  public static function fromResource($resource) {
    return new static(Collection::fromResource($resource));
  }

  /**
   * @return static
   */
  #[\ReturnTypeWillChange]
  public static function fromString(string $string, string $delimiter = '') {
    return new static(Collection::fromString($string, $delimiter));
  }

  public function get($key, $default = NULL): CollectionInterface {
    return $this->decorated->get($key, $default);
  }

  public function getIterator(): Iterator {
    return $this->decorated->getIterator();
  }

  public function group(): CollectionInterface {
    return $this->decorated->group();
  }

  public function groupBy(callable $callable): CollectionInterface {
    return $this->decorated->groupBy($callable);
  }

  public function has(callable ...$callbacks): bool {
    return $this->decorated->has(...$callbacks);
  }

  public function head(): CollectionInterface {
    return $this->decorated->head();
  }

  public function ifThenElse(callable $condition, callable $then, ?callable $else = NULL): CollectionInterface {
    return $this->decorated->ifThenElse($condition, $then, $else);
  }

  public function implode(string $glue = ''): CollectionInterface {
    return $this->decorated->implode($glue);
  }

  public function init(): CollectionInterface {
    return $this->decorated->init();
  }

  public function inits(): CollectionInterface {
    return $this->decorated->inits();
  }

  public function intersect(...$values): CollectionInterface {
    return $this->decorated->intersect(...$values);
  }

  public function intersectKeys(...$keys): CollectionInterface {
    return $this->decorated->intersectKeys(...$keys);
  }

  public function intersperse($element, int $every = 1, int $startAt = 0): CollectionInterface {
    return $this->decorated->intersperse($element, $every, $startAt);
  }

  public function isEmpty(): bool {
    return $this->decorated->isEmpty();
  }

  public function jsonSerialize(): array {
    return $this->decorated->jsonSerialize();
  }

  public function key(int $index = 0) {
    return $this->decorated->key($index);
  }

  public function keys(): CollectionInterface {
    return $this->decorated->keys();
  }

  public function last(): CollectionInterface {
    return $this->decorated->last();
  }

  public function limit(int $count = -1, int $offset = 0): CollectionInterface {
    return $this->decorated->limit($count, $offset);
  }

  public function lines(): CollectionInterface {
    return $this->decorated->lines();
  }

  public function map(callable $callback): CollectionInterface {
    return $this->decorated->map($callback);
  }

  public function mapN(callable ...$callbacks): CollectionInterface {
    return $this->decorated->mapN(...$callbacks);
  }

  public function match(callable $callback, ?callable $matcher = NULL): bool {
    return $this->decorated->match($callback, $matcher);
  }

  public function matching(Criteria $criteria): CollectionInterface {
    return $this->decorated->matching($criteria);
  }

  public function merge(iterable ...$sources): CollectionInterface {
    return $this->decorated->merge(...$sources);
  }

  public function normalize(): CollectionInterface {
    return $this->decorated->normalize();
  }

  public function nth(int $step, int $offset = 0): CollectionInterface {
    return $this->decorated->nth($step, $offset);
  }

  public function nullsy(): bool {
    return $this->decorated->nullsy();
  }

  public function pack(): CollectionInterface {
    return $this->decorated->pack();
  }

  public function pad(int $size, $value): CollectionInterface {
    return $this->decorated->pad($size, $value);
  }

  public function pair(): CollectionInterface {
    return $this->decorated->pair();
  }

  public function partition(callable ...$callbacks): CollectionInterface {
    return $this->decorated->partition(...$callbacks);
  }

  public function permutate(): CollectionInterface {
    return $this->decorated->permutate();
  }

  public function pipe(callable ...$callbacks): CollectionInterface {
    return $this->decorated->pipe(...$callbacks);
  }

  public function pluck($pluck, $default = NULL): CollectionInterface {
    return $this->decorated->pluck($pluck, $default);
  }

  public function prepend(...$items): CollectionInterface {
    return $this->decorated->prepend(...$items);
  }

  public function product(iterable ...$iterables): CollectionInterface {
    return $this->decorated->product(...$iterables);
  }

  public function random(int $size = 1, ?int $seed = NULL): CollectionInterface {
    return $this->decorated->random($size, $seed);
  }

  /**
   * @return static
   */
  #[\ReturnTypeWillChange]
  public static function range(float $start = 0.0, float $end = INF, float $step = 1.0): CollectionInterface {
    return new static(Collection::range($start, $end, $step));
  }

  public function reduce(callable $callback, $initial = NULL): CollectionInterface {
    return $this->decorated->reduce($callback, $initial);
  }

  public function reduction(callable $callback, $initial = NULL): CollectionInterface {
    return $this->decorated->reduction($callback, $initial);
  }

  public function reject(callable ...$callbacks): CollectionInterface {
    return $this->decorated->reject(...$callbacks);
  }

  public function reverse(): CollectionInterface {
    return $this->decorated->reverse();
  }

  public function rsample(float $probability): CollectionInterface {
    return $this->decorated->rsample($probability);
  }

  public function same(CollectionInterface $other, ?callable $comparatorCallback = NULL): bool {
    return $this->decorated->same($other, $comparatorCallback);
  }

  public function scale(float $lowerBound, float $upperBound, float $wantedLowerBound = 0.0, float $wantedUpperBound = 1.0, float $base = 0.0): CollectionInterface {
    return $this->decorated->scale($lowerBound, $upperBound, $wantedLowerBound, $wantedUpperBound, $base);
  }

  public function scanLeft(callable $callback, $initial = NULL): CollectionInterface {
    return $this->decorated->scanLeft($callback, $initial);
  }

  public function scanLeft1(callable $callback): CollectionInterface {
    return $this->decorated->scanLeft1($callback);
  }

  public function scanRight(callable $callback, $initial = NULL): CollectionInterface {
    return $this->decorated->scanRight($callback, $initial);
  }

  public function scanRight1(callable $callback): CollectionInterface {
    return $this->decorated->scanRight1($callback);
  }

  public function shuffle(?int $seed = NULL): CollectionInterface {
    return $this->decorated->shuffle($seed);
  }

  public function since(callable ...$callbacks): CollectionInterface {
    return $this->decorated->since(...$callbacks);
  }

  public function slice(int $offset, int $length = -1): CollectionInterface {
    return $this->decorated->slice($offset, $length);
  }

  public function sort(int $type = Operation\Sortable::BY_VALUES, ?callable $callback = NULL): CollectionInterface {
    return $this->decorated->sort($type, $callback);
  }

  public function span(callable ...$callbacks): CollectionInterface {
    return $this->decorated->span(...$callbacks);
  }

  public function split(int $type = Operation\Splitable::BEFORE, callable ...$callbacks): CollectionInterface {
    return $this->decorated->split($type, ...$callbacks);
  }

  public function squash(): CollectionInterface {
    return $this->decorated->squash();
  }

  public function strict(?callable $callback = NULL): CollectionInterface {
    return $this->decorated->strict($callback);
  }

  public function tail(): CollectionInterface {
    return $this->decorated->tail();
  }

  public function tails(): CollectionInterface {
    return $this->decorated->tails();
  }

  public function takeWhile(callable ...$callbacks): CollectionInterface {
    return $this->decorated->takeWhile(...$callbacks);
  }

  /**
   * @return static
   */
  #[\ReturnTypeWillChange]
  public static function times(int $number = 0, ?callable $callback = NULL): CollectionInterface {
    return new static(Collection::times($number, $callback));
  }

  public function transpose(): CollectionInterface {
    return $this->decorated->transpose();
  }

  public function truthy(): bool {
    return $this->decorated->truthy();
  }

  /**
   * @return static
   */
  #[\ReturnTypeWillChange]
  public static function unfold(callable $callback, ...$parameters): CollectionInterface {
    return new static(Collection::unfold($callback, ...$parameters));
  }

  public function unlines(): CollectionInterface {
    return $this->decorated->unlines();
  }

  public function unpack(): CollectionInterface {
    return $this->decorated->unpack();
  }

  public function unpair(): CollectionInterface {
    return $this->decorated->unpair();
  }

  public function until(callable ...$callbacks): CollectionInterface {
    return $this->decorated->until(...$callbacks);
  }

  public function unwindow(): CollectionInterface {
    return $this->decorated->unwindow();
  }

  public function unwords(): CollectionInterface {
    return $this->decorated->unwords();
  }

  public function unwrap(): CollectionInterface {
    return $this->decorated->unwrap();
  }

  public function unzip(): CollectionInterface {
    return $this->decorated->unzip();
  }

  public function when(callable $predicate, callable $whenTrue, ?callable $whenFalse = NULL): CollectionInterface {
    return $this->decorated->when($predicate, $whenTrue, $whenFalse);
  }

  public function window(int $size): CollectionInterface {
    return $this->decorated->window($size);
  }

  public function words(): CollectionInterface {
    return $this->decorated->words();
  }

  public function wrap(): CollectionInterface {
    return $this->decorated->wrap();
  }

  public function zip(iterable ...$iterables): CollectionInterface {
    return $this->decorated->zip(...$iterables);
  }

}

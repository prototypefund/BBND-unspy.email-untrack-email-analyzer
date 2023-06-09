# Implement typed collections
Status: Accepted
## Summary
While processing data,
wanting typed collections,
we decided to use IteratorAggregate generics,
to gain IDE support,
accepting some code bloat.

## Context

```php
/**
 * @implements \loophp\collection\Contract\Collection<string, \Psr\Http\Message\UriInterface>
 */
final class UriList extends CollectionBase {

  public function current(int $index = 0, $default = NULL): UriInterface {
    return parent::current($index, $default);
  }

}
```

```php
/**
 * @implements \IteratorAggregate<string, \GuzzleHttp\Psr7\Uri>
 */
final class UriList implements \IteratorAggregate {

  protected array $uris;


  public function getIterator() {
    return new \ArrayIterator($this->uris);
  }

}
```
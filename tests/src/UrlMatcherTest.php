<?php

declare(strict_types=1);

namespace Geeks4change\tests\UntrackEmailAnalyzer;

use Geeks4change\UntrackEmailAnalyzer\Utility\UrlMatcher;
use PHPUnit\Framework\TestCase;

final class UrlMatcherTest extends TestCase {

  /**
   * @dataProvider provideUrlPatcherData
   */
  function testUrlMatcher(?bool $match, string $url, string $urlPattern, array $domains = []): void {
    if (is_null($match)) {
      $this->expectException(\UnexpectedValueException::class);
    }
    $matcher = UrlMatcher::create($urlPattern, $domains);
    $matcher->setCnameResolver(null);
    $this->assertEquals($match, $matcher->match($url));
  }

  function provideUrlPatcherData(): array {
    return [
      'domain matches' =>
        [true, 'foo.bar', 'foo.bar'],
      'domain non-matches' =>
        [false, 'xxx.bar', 'foo.bar'],
      'domain and empty path matches arbitrary path' =>
        [true, 'foo.bar/baz', 'foo.bar'],
      'domain and empty path matches empty path' =>
        [true, 'foo.bar', 'foo.bar'],
      'path and empty domain matches arbitrary domain' =>
        [true, 'foo.bar/baz', '/baz'],
      'path and empty domain matches empty domain' =>
        [true, '/baz', '/baz'],
      'path non-matches' =>
        [false, '/xxx', '/baz'],
      'root path' =>
        [true, '/', '/'],
      'root path non-matches' =>
        [false, '/baz', '/'],
      'path and domain' =>
        [true, 'foo.bar/baz', 'foo.bar/baz'],
      'host and domain throws' =>
        [null, 'foo.bar/baz', 'foo.bar/baz', ['foo.bar']],
      'subdomain matches' =>
        [true, 'foo.bar/', '.bar'],
      'subdomain non-matches' =>
        [false, 'foo.bar/', 'bar'],
      'path pattern matches' =>
        [true, '/baz', '/{}'],
      'path pattern non-matches' =>
        [false, '/baz/boo', '/{}'],
      'long path pattern matches' =>
        [true, '/baz/boo', '/{}/boo'],
      'long path pattern non-matches' =>
        [false, '/baz/boo', '/{}/xxx'],
      'full query matches' =>
        [true, '/baz?a=1', '/baz?a=1'],
      'full query non-matches' =>
        [false, '/baz?a=2', '/baz?a=1'],
      'empty query matches' =>
        [true, '/baz?a=1', '/baz?a='],
      'empty alt query matches' =>
        [true, '/baz?a=1', '/baz?a'],
      'empty query matches empty query' =>
        [true, '/baz?a=', '/baz?a='],
      'empty query matches null query' =>
        [true, '/baz?a', '/baz?a='],
      'null query matches empty query' =>
        [true, '/baz?a=', '/baz?a'],
      'null query matches null query' =>
        [true, '/baz?a', '/baz?a'],
      'wildcard query matches' =>
        [true, '/baz?a=a123b', '/baz?a=a{}b'],
      'query match ignores other keys' =>
        [true, '/baz?a&b', '/baz?a'],
      'multi query matches' =>
        [true, '/baz?a&b', '/baz?a&b'],
      'multi query non-matches' =>
        [false, '/baz?a&b', '/baz?a&b&c'],
    ];
  }

}

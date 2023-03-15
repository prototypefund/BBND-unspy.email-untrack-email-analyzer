# Async premises
Status: Accepted
## Summary
While crafting the redirect resolver,
facing long duration of serial resolving,
decided to explore guzzle promise-based resolving,
faining better speed,
accepting some more effort.

## Context
https://github.com/php-http/httplug/issues/86
https://github.com/php-http/httplug/issues/165
https://github.com/manyou-io/promise-http-client
https://symfony.com/doc/current/rate_limiter.html
https://codereview.stackexchange.com/questions/257312/use-symfony5-native-rate-limiter-to-build-a-guzzleratelimiter-middleware

https://symfony.com/doc/current/http_client.html#concurrent-requests
https://symfony.com/doc/current/http_client.html#redirects
https://symfony.com/doc/current/http_client.html#retry-failed-requests

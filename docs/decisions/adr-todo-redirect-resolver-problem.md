
https://github.com/guzzle/guzzle/issues/2751
https://github.com/guzzle/psr7/pull/334
https://github.com/guzzle/guzzle/issues/2534
https://github.com/guzzle/guzzle/pull/2591 in 7.2, 7.5 does NOT fix

https://stackoverflow.com/questions/46214017/public-linkedin-profile-url-returns-server-status-code-999/49856601
https://stackoverflow.com/questions/27231113/999-error-code-on-head-request-to-linkedin

https://news.phineo.org/d?o000khn000clmm00d0000lyi000000000efkxqvyhtzxyxq2pm4x2dgg23q402&utm_source=campus-nl&utm_medium=email&utm_campaign=mailing-182022
PHP Fatal error:  Uncaught InvalidArgumentException: Status code must be an integer value between 1xx and 5xx. in /home/merlin/Code-Incubator/bbnd/bbnd_analyzer/vendor/guzzlehttp/psr7/src/Response.php:152
Stack trace:
#0 /home/merlin/Code-Incubator/bbnd/bbnd_analyzer/vendor/guzzlehttp/psr7/src/Response.php(99): GuzzleHttp\Psr7\Response->assertStatusCodeRange()
#1 /home/merlin/Code-Incubator/bbnd/bbnd_analyzer/vendor/guzzlehttp/guzzle/src/Handler/EasyHandle.php(81): GuzzleHttp\Psr7\Response->__construct()
#2 /home/merlin/Code-Incubator/bbnd/bbnd_analyzer/vendor/guzzlehttp/guzzle/src/Handler/CurlFactory.php(565): GuzzleHttp\Handler\EasyHandle->createResponse()
#3 [internal function]: GuzzleHttp\Handler\CurlFactory->GuzzleHttp\Handler\{closure}()
#4 /home/merlin/Code-Incubator/bbnd/bbnd_analyzer/vendor/guzzlehttp/guzzle/src/Handler/CurlHandler.php(40): curl_exec()
#5 /home/merlin/Code-Incubator/bbnd/bbnd_analyzer/vendor/guzzlehttp/guzzle/src/Handler/Proxy.php(28): GuzzleHttp\Handler\CurlHandler->__invoke()
#6 /home/merlin/Code-Incubator/bbnd/bbnd_analyzer/vendor/guzzlehttp/guzzle/src/Handler/Proxy.php(51): GuzzleHttp\Handler\Proxy::GuzzleHttp\Handler\{closure}()
#7 /home/merlin/Code-Incubator/bbnd/bbnd_analyzer/vendor/guzzlehttp/guzzle/src/PrepareBodyMiddleware.php(37): GuzzleHttp\Handler\Proxy::GuzzleHttp\Handler\{closure}()
#8 /home/merlin/Code-Incubator/bbnd/bbnd_analyzer/vendor/guzzlehttp/guzzle/src/Middleware.php(29): GuzzleHttp\PrepareBodyMiddleware->__invoke()
#9 /home/merlin/Code-Incubator/bbnd/bbnd_analyzer/vendor/guzzlehttp/guzzle/src/RedirectMiddleware.php(70): GuzzleHttp\Middleware::GuzzleHttp\{closure}()
#10 /home/merlin/Code-Incubator/bbnd/bbnd_analyzer/vendor/guzzlehttp/guzzle/src/RedirectMiddleware.php(115): GuzzleHttp\RedirectMiddleware->__invoke()
#11 /home/merlin/Code-Incubator/bbnd/bbnd_analyzer/vendor/guzzlehttp/guzzle/src/RedirectMiddleware.php(72): GuzzleHttp\RedirectMiddleware->checkRedirect()
#12 /home/merlin/Code-Incubator/bbnd/bbnd_analyzer/vendor/guzzlehttp/promises/src/FulfilledPromise.php(41): GuzzleHttp\RedirectMiddleware->GuzzleHttp\{closure}()
#13 /home/merlin/Code-Incubator/bbnd/bbnd_analyzer/vendor/guzzlehttp/promises/src/TaskQueue.php(48): GuzzleHttp\Promise\FulfilledPromise::GuzzleHttp\Promise\{closure}()
#14 /home/merlin/Code-Incubator/bbnd/bbnd_analyzer/vendor/guzzlehttp/promises/src/Promise.php(248): GuzzleHttp\Promise\TaskQueue->run()
#15 /home/merlin/Code-Incubator/bbnd/bbnd_analyzer/vendor/guzzlehttp/promises/src/Promise.php(224): GuzzleHttp\Promise\Promise->invokeWaitFn()
#16 /home/merlin/Code-Incubator/bbnd/bbnd_analyzer/vendor/guzzlehttp/promises/src/Promise.php(269): GuzzleHttp\Promise\Promise->waitIfPending()
#17 /home/merlin/Code-Incubator/bbnd/bbnd_analyzer/vendor/guzzlehttp/promises/src/Promise.php(226): GuzzleHttp\Promise\Promise->invokeWaitList()
#18 /home/merlin/Code-Incubator/bbnd/bbnd_analyzer/vendor/guzzlehttp/promises/src/Promise.php(62): GuzzleHttp\Promise\Promise->waitIfPending()
#19 /home/merlin/Code-Incubator/bbnd/bbnd_analyzer/vendor/guzzlehttp/guzzle/src/Client.php(182): GuzzleHttp\Promise\Promise->wait()
#20 /home/merlin/Code-Incubator/bbnd/bbnd_analyzer/src/RedirectResolver.php(28): GuzzleHttp\Client->request()
#21 /home/merlin/Code-Incubator/bbnd/bbnd_analyzer/src/Analyzer/RedirectDetector.php(28): Geeks4change\UntrackEmailAnalyzer\RedirectResolver\RedirectResolver->resolveRedirect()
#22 /home/merlin/Code-Incubator/bbnd/bbnd_analyzer/src/Analyzer/RedirectDetector.php(17): Geeks4change\UntrackEmailAnalyzer\Analyzer\RedirectDetector->doDetectRedirect()
#23 /home/merlin/Code-Incubator/bbnd/bbnd_analyzer/src/Analyzer/Analyzer.php(54): Geeks4change\UntrackEmailAnalyzer\Analyzer\RedirectDetector->detectRedirect()
#24 /home/merlin/Code-Incubator/bbnd/bbnd_analyzer/debug/testSummary(23): Geeks4change\UntrackEmailAnalyzer\Analyzer\Analyzer->analyze()
#25 {main}
thrown in /home/merlin/Code-Incubator/bbnd/bbnd_analyzer/vendor/guzzlehttp/psr7/src/Response.php on line 152


```sh
curl 'https://www.linkedin.com/company/skalacampus?utm_source=campus-nl&utm_medium=email&utm_campaign=mailing-182022' \
  -H 'authority: www.linkedin.com' \
  -H 'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9' \
  -H 'accept-language: de-DE,de;q=0.9,en-US;q=0.8,en;q=0.7' \
  -H 'sec-ch-ua: "Not?A_Brand";v="8", "Chromium";v="108"' \
  -H 'sec-ch-ua-mobile: ?0' \
  -H 'sec-ch-ua-platform: "Linux"' \
  -H 'sec-fetch-dest: document' \
  -H 'sec-fetch-mode: navigate' \
  -H 'sec-fetch-site: none' \
  -H 'sec-fetch-user: ?1' \
  -H 'upgrade-insecure-requests: 1' \
  -H 'user-agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Safari/537.36' \
  --compressed
```
```sh
curl 'https://www.linkedin.com/company/skalacampus?utm_source=campus-nl&utm_medium=email&utm_campaign=mailing-182022' \
  -H 'user-agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Safari/537.36' \
  --compressed
```

<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\AnalyzerLogger;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\AnalyzerResult;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\LinkAndImageUrlList;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultSummary;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\NewsletterServicesMatcher\HeadersMatcher\AllServicesHeadersMatcher;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\NewsletterServicesMatcher\UrlsMatcher\AllServicesLinkAndImageUrlListMatcher;
use Geeks4change\UntrackEmailAnalyzer\Globals;
use Geeks4change\UntrackEmailAnalyzer\UrlExtractor\ImagesUrlExtractor;
use Geeks4change\UntrackEmailAnalyzer\UrlExtractor\LinksUrlExtractor;
use Geeks4change\UntrackEmailAnalyzer\UrlExtractor\PixelsUrlExtractor;
use Symfony\Component\DomCrawler\Crawler;
use ZBateson\MailMimeParser\MailMimeParser;

/**
 * Analyzer.
 *
 * Return structure:
 * - $dkimResult @see DKIMResult
 *   - string $status
 *   - array<string> $summaryLines
 * - $headersResult @see HeadersResult
 *   - (iterable) @see HeadersResultPerService
 *     - string $serviceName
 *     - array $headerSingleResultList @see HeaderSingleResult
 *       - string $headerName
 *       - bool $isMatch
 * - $allLinkAndImageUrlsList @see LinkAndImageUrlList
 *   - $linkUrlList @see UrlList
 *   - $imageUrlList @see UrlList
 * - $linkAndImageUrlsMatcherResult @see LinkAndImageUrlListMatcherResult
 *   - $linkUrlsResult @see UrlListMatchersResult
 *   - $imageUrlsResult @see UrlListMatchersResult
 *     - $perServiceResultList @see UrlListPerServiceMatchesList
 *       - (iterable) @see UrlListPerServiceMatches
 *         - $urlsMatchedExactly @see UrlList
 *         - $urlsMatchedByDomain @see UrlList
 *         - $urlsNotMatchedList @see UrlList
 * - $pixelsList @see UrlList
 *   - array $urls @see UrlItem (stringable)
 *     - getUrlObject()
 * - $urlsRedirectInfoList @see LinkAndImageRedirectInfoList
 *   - $linkRedirectInfoList @see UrlRedirectInfoList
 *   - $imageRedirectInfoList @see UrlRedirectInfoList
 *     - (iterable) @see UrlRedirectInfo
 *       - string $originalUrl
 *       - array<string> $redirectUrls
 * - $urlsWithAnalyticsList @see LinkAndImageUrlList
 * - $domainAliasesList @see DomainAliasesList
 *   - (iterable) @see DomainAliases
 *     - string $domain
 *     - array<string> $aliases
 *
 * @api This and its return classes are the only API for the outside.
 */
class Analyzer {

  public function analyze(string $rawMessage): AnalyzerResult {
    $logger = new AnalyzerLogger();

    // Check DKIM.
    $dkimResult = (new DKIMSignatureValidator())->validateDKIMSignature($rawMessage);

    // Parse and find patterns.
    $mailParser = new MailMimeParser();
    $message = $mailParser->parse($rawMessage, FALSE);
    // @todo Consider reporting unusual Mime parts, like more than one text/html part.

    // Analyze headers.
    $headersResult = (new AllServicesHeadersMatcher())->matchHeaders($message);

    $unsubscribeUrlList = (new UnsubscribeLinkExtractor())->extractUnsubscribeLink($message);

    // Analyze body html.
    $html = $message->getHtmlContent();
    // Do we need all the bells and whistles of HtmlPageCrawler and underlying
    // DomCrawler? Not really, but seems to add some namespace and encoding
    // safeguards that can not be wrong.
    $crawler = new Crawler($html);

    // Extract links, images, pixels.
    $linkUrls = (new LinksUrlExtractor($crawler))->extract();
    $imageUrls = (new ImagesUrlExtractor($crawler))->extract();
    $allLinkAndImageUrlsList = new LinkAndImageUrlList($linkUrls, $imageUrls);
    $pixelsResult = (new PixelsUrlExtractor($crawler))->extract();

    // Match link and image urls.
    $matcher = new AllServicesLinkAndImageUrlListMatcher();
    $linkAndImageUrlListResult = $matcher->generateLinkAndImageUrlListResults($allLinkAndImageUrlsList);

    // Fetch all resolved aliases.
    $domainAliasList = (new DomainAliasesResultFetcher())->fetch();

    $urlsWithRedirectList = (new RedirectDetector())->detectRedirect($allLinkAndImageUrlsList, $unsubscribeUrlList);
    $urlWithAnalyticsList = (new AnalyticsDetector())->detectAnalytics($allLinkAndImageUrlsList);

    // @todo Implement summary.
    $aggregated = new ResultDetails(NULL, '');

    Globals::deleteAll();

    $resultSummary = new ResultSummary(
      $dkimResult,
      $headersResult,
      $allLinkAndImageUrlsList,
      $linkAndImageUrlListResult,
      $pixelsResult,
      $unsubscribeUrlList,
      $urlsWithRedirectList,
      $urlWithAnalyticsList,
      $domainAliasList
    );
    return new AnalyzerResult($resultSummary, $logger->freeze());
  }

}

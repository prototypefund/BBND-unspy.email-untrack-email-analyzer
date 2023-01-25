<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\AnalyzerLog\AnalyzerLogger;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\FullErrorResult;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\FullResult;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\FullResultWrapper;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\DKIMResult;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\CnameInfo;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\CnameInfoList;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\HeaderMatch;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\HeaderMatchListPerProvider;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\TypedUrlListPerProvider;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\TypedUrlRedirectInfoList;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\TypedUrlList;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\ResultDetails;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\UrlItem;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\UrlList;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\UrlRedirectInfo;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\UrlRedirectInfoList;
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
 * - $headersResult @see HeaderMatchListPerProvider
 *   - (iterable) @see HeaderMatchListPerProvider
 *     - string $serviceName
 *     - array $headerSingleResultList @see HeaderMatch
 *       - string $headerName
 *       - bool $isMatch
 * - $allLinkAndImageUrlsList @see TypedUrlList
 *   - $typeLink @see UrlList
 *   - $typeImage @see UrlList
 * - $exactMatches / $domainMatches @see TypedUrlListPerProvider
 *   - array $perProvider @see TypedUrlList
 *     - $typeLink @see UrlList
 *     - $typeImage @see UrlList
 * - $pixelsList @see UrlList
 *   - array $urls @see UrlItem (stringable)
 *     - getUrlObject()
 * - $urlsRedirectInfoList @see TypedUrlRedirectInfoList
 *   - $linkRedirectInfoList @see UrlRedirectInfoList
 *   - $imageRedirectInfoList @see UrlRedirectInfoList
 *     - (iterable) @see UrlRedirectInfo
 *       - string $originalUrl
 *       - array<string> $redirectUrls
 * - $urlsWithAnalyticsList @see TypedUrlList
 * - $domainAliasesList @see CnameInfoList
 *   - (iterable) @see CnameInfo
 *     - string $domain
 *     - array<string> $aliases
 *
 * @api This and its return classes are the only API for the outside.
 */
class Analyzer {

  protected RedirectDetectorInterface $redirectDetector;

  /**
   * @param \Geeks4change\UntrackEmailAnalyzer\Analyzer\RedirectDetectorInterface $redirectDetector
   */
  public function __construct(RedirectDetectorInterface $redirectDetector) {
    $this->redirectDetector = $redirectDetector;
  }

  public function analyze(string $rawMessage): FullResultWrapper {
    $logger = new AnalyzerLogger();

    try {
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
      $allLinkAndImageUrlsList = new TypedUrlList($linkUrls, $imageUrls);
      $pixelsResult = (new PixelsUrlExtractor($crawler))->extract();

      // Match link and image urls.
      $matcher = new AllServicesLinkAndImageUrlListMatcher();
      ['exact' => $exactMatches, 'domain' => $domainMatches]
        = $matcher->generateMatches($allLinkAndImageUrlsList);

      // Fetch all resolved aliases.
      $domainAliasList = (new DomainAliasesResultFetcher())->fetch();

      $urlsWithRedirectList = ($this->redirectDetector)->detectRedirect($allLinkAndImageUrlsList, $unsubscribeUrlList);
      $urlWithAnalyticsList = (new AnalyticsDetector())->detectAnalytics($allLinkAndImageUrlsList);

      $resultDetails = new ResultDetails(
        $dkimResult,
        $headersResult,
        $allLinkAndImageUrlsList,
        $exactMatches,
        $domainMatches,
        $pixelsResult,
        $unsubscribeUrlList,
        $urlsWithRedirectList,
        $urlWithAnalyticsList,
        $domainAliasList
      );

      $resultSummary = (new ResultSummaryExtractor)
        ->extractResultSummary($resultDetails);
      $resultVerdict = (new ResultVerdictExtractor)
        ->extractResultVerdict($resultSummary);

      $listInfo = (new ListInfoExtractor())->extract($message);
      $analyzerResult = new FullResultWrapper($logger->freeze(), new FullResult($listInfo, $resultVerdict, $resultSummary, $resultDetails));
    } catch (\Throwable $e) {
      $logger->emergency("Exception: {$e->getMessage()}", ['trace' => $e->getTraceAsString()]);
      return new FullResultWrapper($logger->freeze(), NULL);
    }

    Globals::deleteAll();
    return $analyzerResult;
  }

}

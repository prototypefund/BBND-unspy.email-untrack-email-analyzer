<?php

declare(strict_types=1);

namespace Geeks4change\BbndAnalyzer;

use Geeks4change\BbndAnalyzer\DomainNames\DomainNameResolver;
use Geeks4change\BbndAnalyzer\DomElement\DomElementCollection;
use Geeks4change\BbndAnalyzer\Matching\Matcher;
use ZBateson\MailMimeParser\Header\HeaderConsts;
use ZBateson\MailMimeParser\MailMimeParser;

class Analyzer {

  public function analyze(AnalysisBuilderInterface $analysis, string $emailWithHeaders) {
    $analysis->audit('Received $emailWithHeaders.');
    # // LATER Normalize Email => NormalizedEmail.
    # $analysis->audit('Created $normalizedEmailWithHeaders, forgot $emailWithHeaders.');
    // LATER Leverage DKIM normalizer for parsing.
    // @todo Parse Email MIME

    $mailParser = new MailMimeParser();
    $message = $mailParser->parse($emailWithHeaders, FALSE);

    #############################################
    // LATER Report unusual Mime parts, like more than one text/html part.
    $html = $message->getHtmlContent();
    $domainNameResolver = new DomainNameResolver();
    $domElementCollection = DomElementCollection::fromHtml($html, $domainNameResolver);
    $analysis->audit('Extracted link and image URLs.');

    $matcher = new Matcher();
    $matchSummary = $matcher->matchDomElements($domElementCollection);
    $analysis->setMatchSummary($matchSummary);

    // LATER Otherwise, find suspicious URL patterns.
    # $analysis->audit('Found suspicious URL pattern.');
    # $analysis->audit('Found no suspicious URL patterns.');




    #############################################

    $message->getHeader(HeaderConsts::DATE);
    $message->getHeader(HeaderConsts::SUBJECT);
    $message->getHeader(HeaderConsts::FROM);
    $message->getHeader(HeaderConsts::TO);
    $message->getHeader(HeaderConsts::SENDER);
    $message->getHeader(HeaderConsts::RETURN_PATH);
    $message->getHeader('DKIM-Signature');
    $message->getHeader('X-CSA-Complaints');
    $message->getHeader('X-Sender');
    $message->getHeader('X-Receiver');
    $message->getHeader('X-Mailer');
    $message->getHeader('Feedback-ID');
    $message->getHeader('X-Complaints-To');
    $message->getHeader('List-Unsubscribe');
    $message->getHeader('List-Unsubscribe-Post');
    $message->getHeader(HeaderConsts::MESSAGE_ID);


    // LATER Check DKIM signature if exists => DKIM Status.
    # $analysis->audit('Found valid DKIM signature.');
    // LATER Anonymize Email (replace to: with hash).
    # $analysis->audit('Created $recipientHash, forgot "to" from headers.');
    # $analysis->audit('Forgot all headers, except xxx.');

    // @todo Extract relevant headers and match well-known patterns.
    // @todo Extract Creator, Offering, Issue from headers.
    // @todo Match headers with well-known tool header patterns.
    $analysis->audit('Found well-known tool pattern xxx in headers.');
    # $analysis->audit('Could not find well-known tool pattern in headers.');

  }

}

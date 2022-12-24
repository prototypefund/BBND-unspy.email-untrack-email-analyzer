<?php

declare(strict_types=1);

namespace Geeks4change\BbndAnalyzer;

use Geeks4change\BbndAnalyzer\DomainNames\DomainNameResolver;
use Geeks4change\BbndAnalyzer\DomElement\DomElementCollection;
use Geeks4change\BbndAnalyzer\Matching\Matcher;
use Geeks4change\BbndAnalyzer\Utility\DKIMValidatorTool;
use PHPMailer\DKIMValidator\DKIMException;
use PHPMailer\DKIMValidator\Validator;
use ZBateson\MailMimeParser\Header\HeaderConsts;
use ZBateson\MailMimeParser\MailMimeParser;

class Analyzer {

  public function analyze(AnalysisBuilderInterface $analysis, string $emailWithHeaders) {

    // Validate DKIM signature.
    $dkimValidator = new Validator($emailWithHeaders);
    try {
      $dkimResults = $dkimValidator->validate();
    } catch (DKIMException $e) {
      $dkimResults = [];
    }
    // @fixme
    print implode("\n", DKIMValidatorTool::extractSummary($dkimResults)) . "\n";
    print DKIMValidatorTool::extractStatus($dkimResults) . "\n";

    // Parse and find patterns.
    $mailParser = new MailMimeParser();
    $message = $mailParser->parse($emailWithHeaders, FALSE);
    // @todo Consider reporting unusual Mime parts, like more than one text/html part.

    $html = $message->getHtmlContent();
    $domainNameResolver = new DomainNameResolver();
    $domElementCollection = DomElementCollection::fromHtml($html, $domainNameResolver);

    $matcher = new Matcher();
    $matchSummary = $matcher->matchDomElements($domElementCollection);
    $analysis->setMatchSummary($matchSummary);

    // @todo Match typical analytics patterns.

    // @todo If no pattern match, choose an URL / pixel and check redirection.

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


    // @todo Match headers with well-known tool header patterns.
    $matcher->matchHeaders($message);

  }

}

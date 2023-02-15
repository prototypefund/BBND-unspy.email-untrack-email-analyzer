<?php

declare(strict_types=1);
namespace Geeks4change\UntrackEmailAnalyzer\Analyzer2\Result;

use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Result\CnameChain\CnameChainList;
use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Result\Header\HeaderItemInfoBag;
use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Result\Url\UrlItemInfoBag;

/**
 * Analysis summary.
 *
 * @api Will be serialized in persistent storage, any change needs a migration.
 */
final class ResultDetails {

  public function __construct(
    public readonly DKIM\DKIMResult   $dkimResult,
    public readonly HeaderItemInfoBag $headerItemInfoBag,
    public readonly UrlItemInfoBag    $urlItemInfoBag,
    public readonly CnameChainList    $cnameChainList,
  ) {}

  public function anonymize(): self {
    return new self(
      $this->dkimResult,
      $this->headerItemInfoBag->anonymize(),
      $this->urlItemInfoBag->anonymize(),
      $this->cnameChainList,
    );
  }

}

<?php

declare(strict_types=1);
namespace Geeks4change\UntrackEmailAnalyzer\Analyzer2\ResultDetails;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\ResultDetails\DKIMResult;
use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Header\HeaderItemInfoBag;
use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\UrlItemBag;
use Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url\UrlItemInfoBag;
use Geeks4change\UntrackEmailAnalyzer\Analyzer2\ResultDetails\CnameChain\CnameChainList;

/**
 * Analysis summary.
 *
 * @api Will be serialized in persistent storage, any change needs a migration.
 */
final class ResultDetails {

  public function __construct(
    public readonly DKIMResult                 $dkimResult,
    public readonly HeaderItemInfoBag          $headerItemInfoBag,
    public readonly UrlItemBag                 $urlItemBag,
    public readonly UrlItemInfoBag             $urlItemInfoBag,
    public readonly CnameChainList             $cnameChainList,
  ) {}

}

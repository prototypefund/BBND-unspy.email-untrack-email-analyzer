<?php

declare(strict_types=1);
namespace Geeks4change\BbndAnalyzer\Analysis\Summary;

/**
 * Analysis summary.
 *
 * @api Will be serialized in persistent storage, any change needs a migration.
 */
final class Summary {

  protected ServiceSummary $serviceSummary;

  protected MayNeedResearchSummary $mayNeedResearch;

  protected DKIMSummary $dkimSummary;

  protected HeaderSummary $headersSummary;

  protected DomElementSummary $linksSummary;

  protected DomElementSummary $imagesSummary;

  /**
   * @var array<\Geeks4change\BbndAnalyzer\DomainAlias\DomainAliasResolutionBase>
   */
  protected array $domainAliasList;

}

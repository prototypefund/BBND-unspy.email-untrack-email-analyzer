<?php

declare(strict_types=1);

namespace Geeks4change\BbndAnalyzer\Analysis\Summary;

use Geeks4change\BbndAnalyzer\DomainAlias\DomainAliasResolutionBase;

/**
 * @api Will be serialized in persistent storage, any change needs a migration.
 */
final class DomainAliasSummary extends DomainAliasResolutionBase {}

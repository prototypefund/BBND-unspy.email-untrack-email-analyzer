<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer2\Data\Url;

final class UrlRedirect {

  public function __construct(
    public readonly UrlItemType $type,
    public readonly string      $url,
    public readonly ?string     $redirect,
  ) {}

}

<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\Result\Url\Redirect;

use Geeks4change\UntrackEmailAnalyzer\Utility\Anon;

final class RedirectInfo {

  public function __construct(
    public readonly ?string     $redirect,
  ) {}

  public function anonymize(): self {
    return new self(
      Anon::url($this->redirect),
    );
  }

}

<?php

declare(strict_types=1);

namespace Geeks4change\BbndAnalyzer\Analysis\Summary;

/**
 * Url summary.
 *
 * Effectively a string, but convenience methods may be added.
 *
 * Purposefully not coupled to any URL class.
 *
 * @api Will be serialized in persistent storage, any change needs a migration.
 */
final class UrlSummary {

  protected $url;

  /**
   * @param $url
   */
  public function __construct($url) {
    $this->url = $url;
  }

  /**
   * @return mixed
   */
  public function getUrl() {
    return $this->url;
  }


}
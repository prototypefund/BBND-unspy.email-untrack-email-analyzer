<?php

declare(strict_types=1);

namespace Geeks4change\BbndAnalyzer;

use Geeks4change\BbndAnalyzer\AnalyzerResult\MayNeedResearch;
use Geeks4change\BbndAnalyzer\DomainAliases\DomainAliasesResolver;
use Geeks4change\BbndAnalyzer\ServicesMatcher\ToolPatternRepository;

final class Globals {

  protected static ?self $singleton;

  public static function get(): self {
    if (!isset(self::$singleton)) {
      self::$singleton = new static();
    }
    return self::$singleton;
  }

  public static function deleteAll() {
    self::$singleton = NULL;
  }

  protected ToolPatternRepository $serviceInfoRepository;

  public function getServiceInfoRepository(): ToolPatternRepository {
    if (!isset($this->serviceInfoRepository)) {
      $this->serviceInfoRepository = new ToolPatternRepository();
    }
    return $this->serviceInfoRepository;
  }

  protected DomainAliasesResolver $domainAliasesResolver;

  public function getDomainAliasesResolver() {
    if (!isset($this->domainAliasesResolver)) {
      $this->domainAliasesResolver = new DomainAliasesResolver();
    }
    return $this->domainAliasesResolver;
  }

  protected MayNeedResearch $mayNeedResearch;

  /**
   * @return \Geeks4change\BbndAnalyzer\AnalyzerResult\MayNeedResearch
   */
  public function getMayNeedResearch(): MayNeedResearch {
    if (!isset($this->mayNeedResearch)) {
      $this->mayNeedResearch = new MayNeedResearch();
    }
    return $this->mayNeedResearch;
  }

}

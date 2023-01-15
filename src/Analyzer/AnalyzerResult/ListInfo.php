<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult;

use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\Arrayable\ToArrayInterface;
use Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult\Arrayable\ToArrayTrait;

final class ListInfo implements ToArrayInterface {

  use ToArrayTrait;

  protected string $emailLabel;
  protected string $emailAddress;
  protected ?string $listId;

  /**
   * @param string $emailLabel
   * @param string $emailAddress
   * @param string|null $listId
   */
  public function __construct(string $emailLabel, string $emailAddress, ?string $listId) {
    $this->emailLabel = $emailLabel;
    $this->emailAddress = $emailAddress;
    $this->listId = $listId;
  }

  /**
   * @return string
   */
  public function getEmailLabel(): string {
    return $this->emailLabel;
  }

  /**
   * @return string
   */
  public function getEmailAddress(): string {
    return $this->emailAddress;
  }

  /**
   * @return string|null
   */
  public function getListId(): ?string {
    return $this->listId;
  }

}

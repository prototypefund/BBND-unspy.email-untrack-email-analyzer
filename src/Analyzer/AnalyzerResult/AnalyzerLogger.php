<?php

declare(strict_types=1);

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer\AnalyzerResult;

use Logger\Loggers\ArrayLogger;
use loophp\collection\Collection;
use Symfony\Component\Yaml\Yaml;

final class AnalyzerLogger extends ArrayLogger {

  public function freeze(): AnalyzerLog {
    $messages = Collection::fromIterable($this->getMessages())
      ->map(fn(array $item) => self::mapMessage($item))
      ->all();
    return new AnalyzerLog($messages);
  }

  protected static function mapMessage(array $item): string {
    ['level' => $level, 'message' => $message, 'context' => $context,] = $item;
    $levelPrefix = str_pad(strtoupper($level), 10, ' ');
    $fullMessage = $message;
    if ($context) {
      $fullMessage .= "\n\nContext:\n" . Yaml::dump($context, 99);
    }
    return $fullMessage;
  }

}

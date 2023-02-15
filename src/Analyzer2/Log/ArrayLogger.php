<?php
namespace Logger\Loggers;

namespace Geeks4change\UntrackEmailAnalyzer\Analyzer2\Log;

use Psr\Log\AbstractLogger;

/**
 * Forked ArrayLogger from logger/essentials.
 *
 * Because that package is stuck on psr/logger:^1 and everyone uses ^3,
 * consolidation/logger even requires it.
 */
class ArrayLogger extends AbstractLogger {
	/** @var array<int, array{level: string, message: string, context: array<string, mixed>}> */
	private $lines = [];

	/**
	 * Logs with an arbitrary level.
	 *
	 * @inheritDoc
	 */
	public function log($level, $message, array $context = []): void {
		$this->lines[] = [
			'level' => $level,
			'message' => $message,
			'context' => $context,
		];
	}

	/**
	 * @return array<int, array{level: string, message: string, context: array<string, mixed>}>
	 */
	public function getMessages(): array {
		return $this->lines;
	}

	/**
	 * @return $this
	 */
	public function clearAll(): self {
		$this->lines = [];
		return $this;
	}
}

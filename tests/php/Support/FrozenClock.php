<?php
/**
 * @package MemberKey
 */

namespace MemberKey\Tests\Support;

use DateTimeImmutable;
use MemberKey\Clock;

final class FrozenClock implements Clock {

	/**
	 * @var DateTimeImmutable
	 */
	private $now;

	public function __construct( DateTimeImmutable $now ) {
		$this->now = $now;
	}

	public function now(): DateTimeImmutable {
		return $this->now;
	}

	public function set( DateTimeImmutable $now ): void {
		$this->now = $now;
	}

	public function advance( string $modifier ): void {
		$this->now = $this->now->modify( $modifier );
	}
}

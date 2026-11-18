<?php
/**
 * @package MemberKey
 */

namespace MemberKey;

use DateTimeImmutable;

/**
 * Source of "now" for all domain logic, so tests can control time.
 */
interface Clock {

	/**
	 * Current moment, in UTC.
	 */
	public function now(): DateTimeImmutable;
}

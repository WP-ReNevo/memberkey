<?php
/**
 * Base test case: real WordPress, with the Clock and Mail adapters replaced by test doubles.
 *
 * @package MemberKey
 */

namespace MemberKey\Tests;

use MemberKey\Tests\Support\FrozenClock;
use MemberKey\Tests\Support\RecordingMailer;
use WP_UnitTestCase;

abstract class TestCase extends WP_UnitTestCase {

	/**
	 * @var FrozenClock
	 */
	protected $clock;

	/**
	 * @var RecordingMailer
	 */
	protected $mailer;

	public function set_up() {
		parent::set_up();

		$this->clock  = new FrozenClock( new \DateTimeImmutable( '2026-01-15 10:00:00', new \DateTimeZone( 'UTC' ) ) );
		$this->mailer = new RecordingMailer();

		memberkey()->use_clock( $this->clock );
		memberkey()->use_mailer( $this->mailer );
	}

	public function tear_down() {
		memberkey()->reset_adapters();
		parent::tear_down();
	}
}

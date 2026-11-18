<?php
/**
 * @package MemberKey
 */

namespace MemberKey;

use MemberKey\Mail\Mailer;
use MemberKey\Mail\WpMailer;

final class Plugin {

	/**
	 * @var Plugin|null
	 */
	private static $instance;

	/**
	 * @var Clock|null
	 */
	private $clock;

	/**
	 * @var Mailer|null
	 */
	private $mailer;

	public static function instance(): self {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function boot(): void {
		add_action( 'plugins_loaded', array( Installer::class, 'maybe_upgrade' ) );
		add_action( 'rest_api_init', array( new Rest\StatusController(), 'register_routes' ) );
		add_action( 'init', array( new Assets(), 'register' ) );
	}

	public function clock(): Clock {
		if ( null === $this->clock ) {
			$this->clock = new SystemClock();
		}
		return $this->clock;
	}

	public function mailer(): Mailer {
		if ( null === $this->mailer ) {
			$this->mailer = new WpMailer();
		}
		return $this->mailer;
	}

	public function use_clock( Clock $clock ): void {
		$this->clock = $clock;
	}

	public function use_mailer( Mailer $mailer ): void {
		$this->mailer = $mailer;
	}

	public function reset_adapters(): void {
		$this->clock  = null;
		$this->mailer = null;
	}
}

<?php
/**
 * @package MemberKey
 */

namespace MemberKey\Tests\Support;

use MemberKey\Mail\Email;
use MemberKey\Mail\Mailer;

final class RecordingMailer implements Mailer {

	/**
	 * @var Email[]
	 */
	private $sent = array();

	public function send( Email $email ): bool {
		$this->sent[] = $email;
		return true;
	}

	/**
	 * @return Email[]
	 */
	public function sent(): array {
		return $this->sent;
	}

	/**
	 * @return Email[]
	 */
	public function sent_to( string $address ): array {
		return array_values(
			array_filter(
				$this->sent,
				static function ( Email $email ) use ( $address ) {
					return $email->to === $address;
				}
			)
		);
	}
}

<?php
/**
 * @package MemberKey
 */

namespace MemberKey\Mail;

final class Email {

	/**
	 * @var string
	 */
	public $to;

	/**
	 * @var string
	 */
	public $subject;

	/**
	 * HTML body.
	 *
	 * @var string
	 */
	public $body;

	public function __construct( string $to, string $subject, string $body ) {
		$this->to      = $to;
		$this->subject = $subject;
		$this->body    = $body;
	}
}

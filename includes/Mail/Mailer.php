<?php
/**
 * @package MemberKey
 */

namespace MemberKey\Mail;

interface Mailer {

	public function send( Email $email ): bool;
}

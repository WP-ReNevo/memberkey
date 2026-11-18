<?php
/**
 * @package MemberKey
 */

namespace MemberKey\Mail;

final class WpMailer implements Mailer {

	public function send( Email $email ): bool {
		return wp_mail( $email->to, $email->subject, $email->body, array( 'Content-Type: text/html; charset=UTF-8' ) );
	}
}

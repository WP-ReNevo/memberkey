<?php
/**
 * @package MemberKey
 */

namespace MemberKey\Tests;

use DateTimeImmutable;
use DateTimeZone;
use MemberKey\Mail\Email;
use MemberKey\Mail\WpMailer;
use MemberKey\SystemClock;

class AdaptersTest extends TestCase {

	public function test_tests_control_the_plugin_clock() {
		$this->clock->set( new DateTimeImmutable( '2027-03-01 08:30:00', new DateTimeZone( 'UTC' ) ) );
		$this->assertSame( '2027-03-01 08:30:00', memberkey()->clock()->now()->format( 'Y-m-d H:i:s' ) );

		$this->clock->advance( '+3 days' );
		$this->assertSame( '2027-03-04 08:30:00', memberkey()->clock()->now()->format( 'Y-m-d H:i:s' ) );
	}

	public function test_system_clock_reports_the_current_moment_in_utc() {
		$now = ( new SystemClock() )->now();

		$this->assertSame( 'UTC', $now->getTimezone()->getName() );
		$this->assertEqualsWithDelta( time(), $now->getTimestamp(), 2 );
	}

	public function test_emails_sent_by_the_plugin_are_recorded_instead_of_delivered() {
		memberkey()->mailer()->send( new Email( 'ana@example.org', 'Welcome', '<p>Hi Ana</p>' ) );

		$this->assertCount( 1, $this->mailer->sent_to( 'ana@example.org' ) );
		$this->assertSame( 'Welcome', $this->mailer->sent()[0]->subject );
		$this->assertFalse( tests_retrieve_phpmailer_instance()->get_sent() );
	}

	public function test_wp_mailer_delivers_html_email_through_wp_mail() {
		reset_phpmailer_instance();

		$sent = ( new WpMailer() )->send( new Email( 'ana@example.org', 'Welcome', '<p>Hi Ana</p>' ) );

		$mail = tests_retrieve_phpmailer_instance()->get_sent();
		$this->assertTrue( $sent );
		$this->assertSame( 'ana@example.org', $mail->to[0][0] );
		$this->assertSame( 'Welcome', $mail->subject );
		$this->assertStringContainsString( '<p>Hi Ana</p>', $mail->body );
		$this->assertStringContainsString( 'Content-Type: text/html', $mail->header );
	}
}

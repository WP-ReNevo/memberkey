<?php
/**
 * @package MemberKey
 */

namespace MemberKey;

/**
 * Table definitions in dbDelta format (see ADR 0002). Datetimes are UTC.
 */
final class Schema {

	/**
	 * @return string[]
	 */
	public static function tables(): array {
		global $wpdb;

		$charset = $wpdb->get_charset_collate();
		$prefix  = $wpdb->prefix;

		return array(
			"CREATE TABLE {$prefix}mk_subscriptions (
  id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  user_id bigint(20) unsigned NOT NULL,
  plan_id bigint(20) unsigned NOT NULL,
  status varchar(20) NOT NULL DEFAULT 'pending',
  billing_type varchar(20) NOT NULL,
  billing_interval smallint(5) unsigned NOT NULL DEFAULT 0,
  billing_unit varchar(10) NOT NULL DEFAULT '',
  amount decimal(13,2) NOT NULL DEFAULT 0.00,
  currency char(3) NOT NULL DEFAULT '',
  payment_method varchar(20) NOT NULL DEFAULT '',
  paypal_subscription_id varchar(191) NOT NULL DEFAULT '',
  mode varchar(10) NOT NULL,
  started_at datetime DEFAULT NULL,
  expires_at datetime DEFAULT NULL,
  cancelled_at datetime DEFAULT NULL,
  created_at datetime NOT NULL,
  updated_at datetime NOT NULL,
  PRIMARY KEY  (id),
  KEY user_id (user_id),
  KEY plan_id (plan_id),
  KEY status_expires (status,expires_at),
  KEY paypal_subscription_id (paypal_subscription_id)
) {$charset};",
			"CREATE TABLE {$prefix}mk_payments (
  id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  subscription_id bigint(20) unsigned NOT NULL,
  user_id bigint(20) unsigned NOT NULL,
  reference varchar(32) NOT NULL,
  type varchar(20) NOT NULL DEFAULT 'initial',
  status varchar(20) NOT NULL DEFAULT 'pending',
  amount decimal(13,2) NOT NULL DEFAULT 0.00,
  currency char(3) NOT NULL DEFAULT '',
  payment_method varchar(20) NOT NULL DEFAULT '',
  paypal_payment_id varchar(191) NOT NULL DEFAULT '',
  mode varchar(10) NOT NULL,
  created_at datetime NOT NULL,
  completed_at datetime DEFAULT NULL,
  updated_at datetime NOT NULL,
  PRIMARY KEY  (id),
  UNIQUE KEY reference (reference),
  KEY subscription_id (subscription_id),
  KEY user_id (user_id),
  KEY status_created (status,created_at),
  KEY paypal_payment_id (paypal_payment_id)
) {$charset};",
			"CREATE TABLE {$prefix}mk_download_log (
  id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  user_id bigint(20) unsigned NOT NULL,
  download_id bigint(20) unsigned NOT NULL,
  downloaded_at datetime NOT NULL,
  PRIMARY KEY  (id),
  KEY download_id (download_id),
  KEY user_id (user_id),
  KEY downloaded_at (downloaded_at)
) {$charset};",
		);
	}
}

<?php
/**
 * Plugin Name:       phpLiteAdmin
 * Description:       This plugin allows you to manage your SQLite database from your WordPress admin panel.
 * Requires at least: 6.0
 * Requires PHP:      7.4
 * Version:           1.0.0
 * Author:            Rediscov
 * Author URI:        https://rediscov.it
 * Text Domain:       phpliteadmin
 * Software used:     phpLiteAdmin
 * Software URI:      https://www.phpliteadmin.org/
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

//include settings page
require_once plugin_dir_path( __FILE__ ) . 'php/settingsPage.php';
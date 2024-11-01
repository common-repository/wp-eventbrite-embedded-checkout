<?php
/**
 * Plugin Name: WP Eventbrite Embedded Checkout
 * Plugin URI: https://wpeventbritecheckout.com/
 * Description: Allows people to buy Eventbrite tickets without leaving your website. Sell tickets right from your WordPress site!
 * Author: Hendra Setiawan
 * Version: 2.1.2
 * Text Domain: wpeec
 * Written by: Hendra Setiawan - https://wpeventbritecheckout.com/
 */
// Exit if accessed directly
if (!defined('ABSPATH'))
    exit;

define("WPEEC_PLUGIN_PATH", plugin_dir_path(__FILE__));

// Admin Functions
require_once( WPEEC_PLUGIN_PATH . 'admin/admin-functions.php' );

// Embed Functions
require_once( WPEEC_PLUGIN_PATH . 'lib/embed-functions.php' );
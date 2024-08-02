<?php
/*
 * Plugin Name: WooCommerce Role Assigner for Purchases
 * Description: Automatically assign a role to any user who makes a purchase via WooCommerce. Quick, lightweight, and completely unintrusive.
 * Version: 1.0.0
 * Requires at least: 6.0
 * Requires PHP: 8.2.4 
 * Author: Markos Kapnakis
 * Author URI: https://github.com/ArceusLegend
 * License: MIT License
 * Requires Plugins:  woocommerce
 */

if (!defined('ABSPATH')) {
    http_response_code(404);
    die();
}
define('WCRA_MAIN_PLUGIN_DIRPATH', __DIR__);
define('WCRA_MAIN_PLUGIN_FILEPATH', __FILE__);
if (!class_exists('WoocommerceRoleAssigner')) {
    class WoocommerceRoleAssigner
    {
        public function __construct()
        {
            define('PLUGIN_ABS_PATH', plugin_dir_path(__FILE__));
            require_once PLUGIN_ABS_PATH . '/vendor/autoload.php';
        }

        public function initialize()
        {
            include_once PLUGIN_ABS_PATH . '/includes/utilities.php';
            include_once PLUGIN_ABS_PATH . '/includes/options-page.php';
            include_once PLUGIN_ABS_PATH . '/includes/main.php';
        }
    }
}

$myPlugin = new WoocommerceRoleAssigner;
$myPlugin->initialize();

<?php
/**
 * Plugin Name: Doctors Custom Post Type
 * Description: Custom post type for doctors with filtering, taxonomies and custom fields.
 * Version: 1.0.0
 * Author: Developer
 * Text Domain: doctors-plugin
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'DOCTORS_PLUGIN_VERSION', '1.0.0' );
define( 'DOCTORS_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'DOCTORS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

require_once DOCTORS_PLUGIN_PATH . 'includes/register-cpt.php';
require_once DOCTORS_PLUGIN_PATH . 'includes/register-taxonomies.php';
require_once DOCTORS_PLUGIN_PATH . 'includes/meta-boxes.php';
require_once DOCTORS_PLUGIN_PATH . 'includes/template-loader.php';
require_once DOCTORS_PLUGIN_PATH . 'includes/archive-filter.php';

add_action( 'plugins_loaded', 'doctors_plugin_init' );

/**
 * Flush rewrite rules on plugin activation.
 *
 * @since 1.0.0
 */
function doctors_rewrite_flush() {
    if ( function_exists( 'doctors_register_cpt' ) ) {
        doctors_register_cpt();
        flush_rewrite_rules();
    }
}

register_activation_hook( __FILE__, 'doctors_rewrite_flush' );

/**
 * Initialize plugin textdomain for translations.
 *
 * @since 1.0.0
 */
function doctors_plugin_init() {
    load_plugin_textdomain( 'doctors-plugin', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}

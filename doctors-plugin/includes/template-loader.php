<?php
/**
 * Template Loader for Doctors Plugin
 * Provides custom templates from the plugin directory
 */

function doctors_template_hierarchy( $template ) {
    if ( is_singular( 'doctors' ) ) {
        $plugin_template = DOCTORS_PLUGIN_PATH . 'templates/single-doctor.php';
        if ( file_exists( $plugin_template ) ) {
            return $plugin_template;
        }
    } elseif ( is_post_type_archive( 'doctors' ) ) {
        $plugin_template = DOCTORS_PLUGIN_PATH . 'templates/archive-doctors.php';
        if ( file_exists( $plugin_template ) ) {
            return $plugin_template;
        }
    }
    return $template;
}

add_filter( 'template_include', 'doctors_template_hierarchy' );

function doctors_enqueue_template_styles() {
    if ( is_singular( 'doctors' ) || is_post_type_archive( 'doctors' ) ) {
        wp_enqueue_style(
            'doctors-templates',
            DOCTORS_PLUGIN_URL . 'templates/doctors-styles.css',
            array(),
            DOCTORS_PLUGIN_VERSION
        );
    }
}

add_action( 'wp_enqueue_scripts', 'doctors_enqueue_template_styles' );

<?php
/**
 * Doctors Plugin - Template Loader
 *
 * Provides custom templates from the plugin directory.
 *
 * @package DoctorsPlugin
 * @since 1.0.0
 */

/**
 * Filter template hierarchy for doctors post type.
 *
 * @since 1.0.0
 * @param string $template The path to the template file.
 * @return string The modified template path.
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

/**
 * Enqueue styles for doctors templates.
 *
 * @since 1.0.0
 */
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

<?php
/**
 * Enqueue Scripts and Styles
 *
 * @package EAD_Rio
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enqueue styles
 */
function ead_rio_enqueue_styles() {
    // Enqueue main theme styles from dist folder
    $main_css_path = get_stylesheet_directory() . '/dist/css/style.css';
    if (file_exists($main_css_path)) {
        wp_enqueue_style(
            'ead-rio-style',
            get_stylesheet_directory_uri() . '/dist/css/style.css',
            [],
            wp_get_theme()->get('Version')
        );
    } else {
        // Fallback to root style.css if dist version doesn't exist
        wp_enqueue_style(
            'ead-rio-style',
            get_stylesheet_uri(),
            [],
            wp_get_theme()->get('Version')
        );
    }

    // Enqueue Google Fonts
    wp_enqueue_style(
        'ead-rio-fonts',
        'https://fonts.googleapis.com/css2?family=Titillium+Web:wght@400;500;600;700&family=Roboto:wght@300;400;500;700&display=swap',
        [],
        null
    );
}
add_action('wp_enqueue_scripts', 'ead_rio_enqueue_styles');

/**
 * Enqueue scripts
 */
function ead_rio_enqueue_scripts() {
    // Enqueue main theme script from dist folder if it exists
    $theme_js_path = get_stylesheet_directory() . '/dist/js/src/theme.js';
    if (file_exists($theme_js_path)) {
        wp_enqueue_script(
            'ead-rio-theme',
            get_stylesheet_directory_uri() . '/dist/js/src/theme.js',
            [],
            wp_get_theme()->get('Version'),
            true
        );
    }
}
add_action('wp_enqueue_scripts', 'ead_rio_enqueue_scripts');

/**
 * Add module type to theme script
 */
function ead_rio_add_module_type($tag, $handle) {
    if ('ead-rio-theme' === $handle) {
        return str_replace('<script ', '<script type="module" ', $tag);
    }
    return $tag;
}
add_filter('script_loader_tag', 'ead_rio_add_module_type', 10, 2);

/**
 * Register widget styles
 */
function ead_rio_register_widget_styles() {
    wp_register_style(
        'cards-module-widget',
        get_template_directory_uri() . '/dist/css/components/widgets/cards-module/cards-module.css',
        [],
        wp_get_theme()->get('Version')
    );
}
add_action('wp_enqueue_scripts', 'ead_rio_register_widget_styles');
<?php
/**
 * EAD Rio - Child Theme for Hello Elementor
 *
 * @package EAD_Rio
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Enqueue scripts and styles
 */
function ead_rio_enqueue_styles() {
    // Enqueue parent theme styles
    wp_enqueue_style(
        'hello-elementor',
        get_template_directory_uri() . '/style.css',
        [],
        wp_get_theme(get_template())->get('Version')
    );

    // Enqueue child theme styles with high priority
    wp_enqueue_style(
        'ead-rio-style',
        get_stylesheet_uri(),
        ['hello-elementor'],
        wp_get_theme()->get('Version')
    );
}
add_action('wp_enqueue_scripts', 'ead_rio_enqueue_styles', 20); // Higher priority

/**
 * Register widget styles
 */
function ead_rio_register_widget_styles() {
    wp_register_style(
        'cards-module-widget',
        get_stylesheet_directory_uri() . '/assets/css/widgets/cards-module/cards-module.css',
        [],
        wp_get_theme()->get('Version')
    );
}
add_action('wp_enqueue_scripts', 'ead_rio_register_widget_styles');

/**
 * Register Custom Elementor Widgets
 */
function ead_rio_register_elementor_widgets($widgets_manager) {
    require_once(__DIR__ . '/widgets/cards-module/cards-module-widget.php');
    $widgets_manager->register(new \Cards_Module_Widget());
}
add_action('elementor/widgets/register', 'ead_rio_register_elementor_widgets');



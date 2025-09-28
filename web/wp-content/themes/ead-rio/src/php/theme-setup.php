<?php
/**
 * Theme Setup Functions
 *
 * @package EAD_Rio
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Theme setup
 */
function ead_rio_theme_setup() {
    // Add theme support for various features
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo');
    add_theme_support('custom-header');
    add_theme_support('custom-background');
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'script',
        'style'
    ));

    // Add theme support for responsive embeds
    add_theme_support('responsive-embeds');

    // Add theme support for editor styles
    add_theme_support('editor-styles');

    // Add theme support for wide alignment
    add_theme_support('align-wide');

    // Register navigation menus
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'ead-rio'),
        'footer' => __('Footer Menu', 'ead-rio'),
    ));

    // Set content width
    if (!isset($content_width)) {
        $content_width = 1200;
    }
}
add_action('after_setup_theme', 'ead_rio_theme_setup');

/**
 * Initialize unified component system (PHP auto-loading + style management)
 */
function ead_rio_init_components() {
    $component_loader_path = get_template_directory() . '/src/includes/component-loader.php';
    if (file_exists($component_loader_path)) {
        require_once $component_loader_path;

        // Initialize the unified component loader
        // This handles both PHP auto-loading and style management
        get_component_loader();
    }
}
add_action('after_setup_theme', 'ead_rio_init_components', 5);

/**
 * Disable WordPress admin bar for all users except administrators
 */
function ead_rio_disable_admin_bar() {
    if (!current_user_can('administrator') && !is_admin()) {
        show_admin_bar(false);
    }
}
add_action('after_setup_theme', 'ead_rio_disable_admin_bar');
<?php
/**
 * Theme Filters and Customizations
 *
 * @package EAD_Rio
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Custom excerpt length
 */
function ead_rio_excerpt_length($length) {
    return 30;
}
add_filter('excerpt_length', 'ead_rio_excerpt_length');

/**
 * Custom excerpt more
 */
function ead_rio_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'ead_rio_excerpt_more');

/**
 * Add custom body classes
 */
function ead_rio_body_classes($classes) {
    // Add class if it's a single course post
    if (is_singular('curso')) {
        $classes[] = 'single-curso';
    }

    // Add class for the theme
    $classes[] = 'ead-rio-theme';

    return $classes;
}
add_filter('body_class', 'ead_rio_body_classes');
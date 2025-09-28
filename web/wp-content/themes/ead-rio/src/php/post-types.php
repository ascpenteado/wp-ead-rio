<?php
/**
 * Custom Post Types and Taxonomies
 *
 * @package EAD_Rio
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register custom post types
 */
function ead_rio_register_custom_post_types() {
    // Register 'curso' post type if it doesn't exist
    if (!post_type_exists('curso')) {
        register_post_type('curso', array(
            'labels' => array(
                'name' => __('Cursos', 'ead-rio'),
                'singular_name' => __('Curso', 'ead-rio'),
                'add_new' => __('Add New', 'ead-rio'),
                'add_new_item' => __('Add New Curso', 'ead-rio'),
                'edit_item' => __('Edit Curso', 'ead-rio'),
                'new_item' => __('New Curso', 'ead-rio'),
                'view_item' => __('View Curso', 'ead-rio'),
                'search_items' => __('Search Cursos', 'ead-rio'),
                'not_found' => __('No cursos found', 'ead-rio'),
                'not_found_in_trash' => __('No cursos found in Trash', 'ead-rio'),
            ),
            'public' => true,
            'has_archive' => true,
            'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
            'rewrite' => array('slug' => 'curso'),
            'show_in_rest' => true,
        ));
    }
}
add_action('init', 'ead_rio_register_custom_post_types');
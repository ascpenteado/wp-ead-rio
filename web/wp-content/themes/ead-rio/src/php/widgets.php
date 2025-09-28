<?php
/**
 * Widget and Sidebar Registration
 *
 * @package EAD_Rio
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register sidebars and widget areas
 */
function ead_rio_widgets_init() {
    register_sidebar(array(
        'name'          => __('Primary Sidebar', 'ead-rio'),
        'id'            => 'sidebar-1',
        'description'   => __('Add widgets here to appear in your sidebar.', 'ead-rio'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ));

    register_sidebar(array(
        'name'          => __('Footer Area', 'ead-rio'),
        'id'            => 'footer-1',
        'description'   => __('Add widgets here to appear in your footer.', 'ead-rio'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
}
add_action('widgets_init', 'ead_rio_widgets_init');
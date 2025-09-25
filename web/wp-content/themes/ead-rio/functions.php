<?php
/**
 * EAD Rio Theme Functions
 *
 * @package EAD_Rio
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
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
 * Include component loader for automatic style loading
 */
function ead_rio_load_component_loader() {
    $component_loader_path = get_template_directory() . '/includes/component-loader.php';
    if (file_exists($component_loader_path)) {
        require_once $component_loader_path;
    }
}
add_action('after_setup_theme', 'ead_rio_load_component_loader');

/**
 * Enqueue scripts and styles
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

/**
 * Add module type to theme script
 */
function ead_rio_add_module_type($tag, $handle) {
    if ('ead-rio-theme' === $handle) {
        return str_replace('<script ', '<script type="module" ', $tag);
    }
    return $tag;
}
add_action('wp_enqueue_scripts', 'ead_rio_enqueue_scripts');
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

/**
 * Register Custom Elementor Widgets (if Elementor is active)
 */
function ead_rio_register_elementor_widgets($widgets_manager) {
    $cards_widget_path = get_template_directory() . '/components/widgets/cards-module/cards-module-widget.php';
    if (file_exists($cards_widget_path)) {
        require_once($cards_widget_path);
        if (class_exists('Cards_Module_Widget')) {
            $widgets_manager->register(new \Cards_Module_Widget());
        }
    }
}

// Only register Elementor widgets if Elementor is active
if (did_action('elementor/loaded')) {
    add_action('elementor/widgets/register', 'ead_rio_register_elementor_widgets');
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

/**
 * Disable WordPress admin bar for all users except administrators
 */
function ead_rio_disable_admin_bar() {
    if (!current_user_can('administrator') && !is_admin()) {
        show_admin_bar(false);
    }
}
add_action('after_setup_theme', 'ead_rio_disable_admin_bar');

/**
 * Add support for custom post types (if needed)
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
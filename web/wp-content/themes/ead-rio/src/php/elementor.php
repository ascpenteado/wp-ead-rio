<?php
/**
 * Elementor Integration
 *
 * @package EAD_Rio
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add custom widget categories
 */
function ead_rio_add_elementor_widget_categories($elements_manager) {
    $categories = [];
    $categories['ead-rio-widgets'] = [
        'title' => __('EAD Rio - Widgets', 'ead-rio'),
        'icon' => 'fa fa-plug',
    ];

    $old_categories = $elements_manager->get_categories();
    $categories = array_merge($categories, $old_categories);

    $set_categories = function ($categories) {
        $this->categories = $categories;
    };

    $set_categories->call($elements_manager, $categories);
}

/**
 * Register Custom Elementor Widgets (if Elementor is active)
 */
function ead_rio_register_elementor_widgets($widgets_manager) {
    $cards_widget_path = get_stylesheet_directory() . '/src/components/widgets/rio-cards-module/rio-cards-module-widget.php';
    if (file_exists($cards_widget_path)) {
        require_once($cards_widget_path);
        if (class_exists('Rio_Cards_Module_Widget')) {
            $widgets_manager->register(new \Rio_Cards_Module_Widget());
        }
    }

    $button_widget_path = get_stylesheet_directory() . '/src/components/atoms/rio-button/rio-button-widget.php';
    if (file_exists($button_widget_path)) {
        require_once($button_widget_path);
        if (class_exists('Rio_Button_Widget')) {
            $widgets_manager->register(new \Rio_Button_Widget());
        }
    }
}

// Only register Elementor widgets if Elementor is active
if (did_action('elementor/loaded')) {
    add_action('elementor/elements/categories_registered', 'ead_rio_add_elementor_widget_categories');
    add_action('elementor/widgets/register', 'ead_rio_register_elementor_widgets');
}
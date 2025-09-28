<?php

if (!defined('ABSPATH')) {
    exit;
}

// Load base widget class
require_once get_stylesheet_directory() . '/src/includes/widgets/abstracts/base-widget.php';

class Rio_Cards_Module_Widget extends Base_Widget {

    /**
     * Get widget configuration
     */
    protected function get_widget_config() {
        return require __DIR__ . '/rio-cards-module-config.php';
    }

    /**
     * Render widget content
     */
    protected function render() {
        $settings = $this->get_settings_for_display();

        // Build query for courses
        $query_args = [
            'post_type' => 'curso',
            'posts_per_page' => $settings['posts_per_page'],
            'post_status' => 'publish',
        ];

        $posts = new WP_Query($query_args);

        if (!$posts->have_posts()) {
            echo '<p>' . __('Nenhum curso encontrado.', 'ead-rio') . '</p>';
            return;
        }

        // Render template with data
        $this->render_template([
            'posts' => $posts,
            'settings' => $settings,
            'columns' => $settings['columns'],
        ]);

        wp_reset_postdata();
    }
}
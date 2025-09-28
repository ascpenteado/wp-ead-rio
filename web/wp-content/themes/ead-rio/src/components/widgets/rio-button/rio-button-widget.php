<?php
/**
 * Rio Button Widget
 *
 * @package EAD_Rio
 */

if (!defined('ABSPATH')) {
    exit;
}

// Load base widget class
require_once get_stylesheet_directory() . '/src/includes/widgets/abstracts/base-widget.php';

/**
 * Rio Button Widget Class
 */
class Rio_Button_Widget extends Base_Widget {

    /**
     * Get widget configuration
     */
    protected function get_widget_config() {
        return require __DIR__ . '/rio-button-config.php';
    }

    /**
     * Render widget output on the frontend
     */
    protected function render() {
        $settings = $this->get_settings_for_display();

        // Prepare template variables
        $button_args = [
            'text' => $settings['button_text'] ?? __('Click Me', 'ead-rio'),
            'url' => $settings['button_url']['url'] ?? '#',
            'variant' => $settings['button_variant'] ?? 'primary',
            'size' => $settings['button_size'] ?? 'medium',
            'tag' => $settings['button_tag'] ?? 'a',
            'classes' => $settings['css_classes'] ?? '',
            'attributes' => []
        ];

        // Add ID if provided
        if (!empty($settings['button_id'])) {
            $button_args['attributes']['id'] = $settings['button_id'];
        }

        // Add target and nofollow for external links
        if (!empty($settings['button_url']['is_external'])) {
            $button_args['attributes']['target'] = '_blank';
        }
        if (!empty($settings['button_url']['nofollow'])) {
            $button_args['attributes']['rel'] = 'nofollow';
        }

        // Add JavaScript options if enabled
        if ('yes' === ($settings['enable_js_options'] ?? 'no')) {
            $js_options = [];

            if ('yes' === ($settings['disable_on_click'] ?? 'no')) {
                $js_options['disableOnClick'] = true;

                if (!empty($settings['loading_text'])) {
                    $js_options['loadingText'] = $settings['loading_text'];
                }
            }

            if (!empty($js_options)) {
                $button_args['attributes']['data-button-options'] = wp_json_encode($js_options);
            }
        }

        // Render template with data
        $this->render_template([
            'button_args' => $button_args,
            'settings' => $settings,
        ]);
    }
}
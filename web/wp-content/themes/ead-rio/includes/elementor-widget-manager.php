<?php
/**
 * EAD Rio Elementor Widget Manager
 *
 * @package EAD_Rio
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class EAD_Rio_Elementor_Widget_Manager {

    /**
     * Widget configurations
     */
    private $widget_configs = [];

    /**
     * Constructor
     */
    public function __construct() {
        add_action('elementor/elements/categories_registered', [$this, 'add_widget_categories']);
        add_action('elementor/widgets/register', [$this, 'register_widgets']);
        add_action('elementor/frontend/after_enqueue_styles', [$this, 'enqueue_widget_styles']);
        add_action('elementor/editor/after_enqueue_styles', [$this, 'enqueue_editor_styles']);

        $this->load_widget_configs();
    }

    /**
     * Add custom widget categories
     */
    public function add_widget_categories($elements_manager) {
        // Main EAD Rio category
        $elements_manager->add_category(
            'ead-rio-widgets',
            [
                'title' => __('EAD Rio', 'ead-rio'),
                'icon' => 'eicon-gallery-group',
            ],
            1 // Position at the top
        );

        // Sub-categories for better organization
        $elements_manager->add_category(
            'ead-rio-layout',
            [
                'title' => __('EAD Rio - Layout', 'ead-rio'),
                'icon' => 'eicon-section',
            ],
            2
        );

        $elements_manager->add_category(
            'ead-rio-content',
            [
                'title' => __('EAD Rio - Content', 'ead-rio'),
                'icon' => 'eicon-post-content',
            ],
            3
        );

        $elements_manager->add_category(
            'ead-rio-forms',
            [
                'title' => __('EAD Rio - Forms', 'ead-rio'),
                'icon' => 'eicon-form-horizontal',
            ],
            4
        );
    }

    /**
     * Load widget configurations
     */
    private function load_widget_configs() {
        $widgets_dir = get_template_directory() . '/widgets/';

        if (!is_dir($widgets_dir)) {
            return;
        }

        $widget_folders = glob($widgets_dir . '*', GLOB_ONLYDIR);

        foreach ($widget_folders as $folder) {
            $widget_name = basename($folder);
            $config_file = $folder . '/' . $widget_name . '-config.php';

            if (file_exists($config_file)) {
                $config = include $config_file;
                if (is_array($config)) {
                    $this->widget_configs[$widget_name] = $config;
                }
            }
        }
    }

    /**
     * Register all widgets
     */
    public function register_widgets($widgets_manager) {
        foreach ($this->widget_configs as $widget_name => $config) {
            $this->register_single_widget($widgets_manager, $widget_name, $config);
        }
    }

    /**
     * Register a single widget
     */
    private function register_single_widget($widgets_manager, $widget_name, $config) {
        $widget_file = get_template_directory() . '/widgets/' . $widget_name . '/' . $widget_name . '-widget.php';

        if (!file_exists($widget_file)) {
            return;
        }

        require_once $widget_file;

        // Convert widget name to class name
        $class_name = $this->get_widget_class_name($widget_name);

        if (!class_exists($class_name)) {
            return;
        }

        // Check dependencies
        if (isset($config['dependencies']) && !$this->check_dependencies($config['dependencies'])) {
            return;
        }

        try {
            $widgets_manager->register(new $class_name());
        } catch (Exception $e) {
            error_log('EAD Rio Widget Registration Error: ' . $e->getMessage());
        }
    }

    /**
     * Get widget class name from widget name
     */
    private function get_widget_class_name($widget_name) {
        // Convert kebab-case to PascalCase and add Widget suffix
        $parts = explode('-', $widget_name);
        $class_parts = array_map('ucfirst', $parts);
        return implode('_', $class_parts) . '_Widget';
    }

    /**
     * Check widget dependencies
     */
    private function check_dependencies($dependencies) {
        foreach ($dependencies as $plugin => $version) {
            if ($plugin === 'elementor') {
                if (!defined('ELEMENTOR_VERSION')) {
                    return false;
                }
                if (version_compare(ELEMENTOR_VERSION, $version, '<')) {
                    return false;
                }
            }
            // Add more dependency checks as needed
        }
        return true;
    }

    /**
     * Enqueue widget styles
     */
    public function enqueue_widget_styles() {
        foreach ($this->widget_configs as $widget_name => $config) {
            if (isset($config['assets']['styles'])) {
                foreach ($config['assets']['styles'] as $handle => $style) {
                    $this->enqueue_style($handle, $style);
                }
            }
        }
    }

    /**
     * Enqueue editor styles
     */
    public function enqueue_editor_styles() {
        wp_enqueue_style(
            'ead-rio-elementor-editor',
            get_template_directory_uri() . '/assets/css/elementor-editor.css',
            [],
            wp_get_theme()->get('Version')
        );
    }

    /**
     * Enqueue a single style
     */
    private function enqueue_style($handle, $style_config) {
        $src = get_template_directory_uri() . $style_config['src'];

        // Convert SCSS to CSS path if needed
        if (strpos($src, '.scss') !== false) {
            $src = str_replace('.scss', '.css', $src);
            $src = str_replace('/widgets/', '/assets/css/widgets/', $src);
        }

        wp_enqueue_style(
            $handle,
            $src,
            $style_config['deps'] ?? [],
            $style_config['version'] ?? wp_get_theme()->get('Version')
        );
    }

    /**
     * Get widget config
     */
    public function get_widget_config($widget_name) {
        return $this->widget_configs[$widget_name] ?? null;
    }

    /**
     * Get all widget configs
     */
    public function get_all_widget_configs() {
        return $this->widget_configs;
    }

    /**
     * Get widgets by category
     */
    public function get_widgets_by_category($category) {
        $widgets = [];
        foreach ($this->widget_configs as $widget_name => $config) {
            if (isset($config['category']) && $config['category'] === $category) {
                $widgets[$widget_name] = $config;
            }
        }
        return $widgets;
    }
}

// Initialize the widget manager
function ead_rio_init_elementor_widget_manager() {
    if (did_action('elementor/loaded')) {
        new EAD_Rio_Elementor_Widget_Manager();
    }
}
add_action('init', 'ead_rio_init_elementor_widget_manager');
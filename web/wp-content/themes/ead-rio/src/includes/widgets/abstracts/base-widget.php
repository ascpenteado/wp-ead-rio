<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Base Widget Class
 *
 * Abstract base class for Elementor widgets that provides:
 * - Configuration-driven widget creation
 * - Template rendering functionality
 * - Common Elementor integration patterns
 */
abstract class Base_Widget extends \Elementor\Widget_Base {

    /**
     * Widget configuration array
     * @var array
     */
    protected $widget_config = [];

    /**
     * Get widget configuration
     * Must be implemented by child classes
     *
     * @return array Widget configuration
     */
    abstract protected function get_widget_config();

    /**
     * Initialize widget configuration
     */
    public function __construct($data = [], $args = null) {
        $this->widget_config = $this->get_widget_config();
        parent::__construct($data, $args);
    }

    /**
     * Get widget name
     */
    public function get_name() {
        return $this->widget_config['name'] ?? 'base_widget';
    }

    /**
     * Get widget title
     */
    public function get_title() {
        return $this->widget_config['title'] ?? __('Base Widget', 'ead-rio');
    }

    /**
     * Get widget icon
     */
    public function get_icon() {
        return $this->widget_config['icon'] ?? 'eicon-posts-grid';
    }

    /**
     * Get widget categories
     */
    public function get_categories() {
        return isset($this->widget_config['category']) ? [$this->widget_config['category']] : ['general'];
    }

    /**
     * Get widget keywords
     */
    public function get_keywords() {
        return $this->widget_config['keywords'] ?? [];
    }

    /**
     * Get style dependencies
     */
    public function get_style_depends() {
        return $this->widget_config['style_dependencies'] ?? [];
    }

    /**
     * Register widget controls based on configuration
     */
    protected function register_controls() {
        if (!isset($this->widget_config['controls'])) {
            return;
        }

        // Load control builder
        require_once get_stylesheet_directory() . '/src/includes/widgets/controls/control-builder.php';
        $control_builder = new Control_Builder($this);

        foreach ($this->widget_config['controls'] as $section_id => $section_config) {
            $control_builder->build_section($section_id, $section_config);
        }
    }

    /**
     * Render widget template with data
     *
     * @param array $data Data to pass to template
     */
    protected function render_template($data = []) {
        $template_file = $this->widget_config['template'] ?? null;

        if (!$template_file) {
            echo '<p>No template configured for this widget.</p>';
            return;
        }

        $template_path = dirname($this->get_widget_file_path()) . '/' . $template_file;

        if (!file_exists($template_path)) {
            echo '<p>Template file not found: ' . esc_html($template_file) . '</p>';
            return;
        }

        // Extract data for template
        extract($data, EXTR_SKIP);

        // Make data available as $data array as well
        $data = $data;

        include $template_path;
    }

    /**
     * Get the file path of the widget class
     * Used to locate template files relative to widget
     */
    protected function get_widget_file_path() {
        $reflection = new ReflectionClass($this);
        return $reflection->getFileName();
    }

    /**
     * Render widget content
     * Must be implemented by child classes
     */
    // Note: render() method is already defined in parent \Elementor\Widget_Base
    // Child classes should override it as needed
}
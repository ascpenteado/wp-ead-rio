<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Component Loader - Automatic style and script loading for components
 *
 * This class provides automatic loading of component styles and scripts
 * when components are rendered, similar to tree shaking in modern build tools.
 */
class Component_Loader {

    private static $instance = null;
    private $loaded_components = [];
    private $component_styles = [];
    private $component_scripts = [];

    /**
     * Get singleton instance
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_component_assets'], 20);
        add_action('wp_footer', [$this, 'output_inline_styles'], 5);
    }

    /**
     * Register a component for style/script loading
     *
     * @param string $component_name Component identifier
     * @param array $args {
     *     @type string $style_path  Path to SCSS/CSS file relative to theme
     *     @type string $script_path Path to JS file relative to theme (optional)
     *     @type array  $dependencies Style dependencies (optional)
     * }
     */
    public function register_component($component_name, $args = []) {
        $defaults = [
            'style_path' => null,
            'script_path' => null,
            'dependencies' => [],
        ];

        $args = wp_parse_args($args, $defaults);

        if ($args['style_path']) {
            $this->component_styles[$component_name] = $args;
        }

        if ($args['script_path']) {
            $this->component_scripts[$component_name] = $args;
        }
    }

    /**
     * Mark a component as used (to be loaded)
     *
     * @param string $component_name Component identifier
     */
    public function use_component($component_name) {
        if (!in_array($component_name, $this->loaded_components)) {
            $this->loaded_components[] = $component_name;
        }
    }

    /**
     * Enqueue registered component assets
     */
    public function enqueue_component_assets() {
        foreach ($this->loaded_components as $component_name) {
            // Enqueue scripts if registered
            if (isset($this->component_scripts[$component_name])) {
                $script_config = $this->component_scripts[$component_name];
                $script_path = get_template_directory() . '/' . ltrim($script_config['script_path'], '/');

                if (file_exists($script_path)) {
                    wp_enqueue_script(
                        "component-{$component_name}",
                        get_template_directory_uri() . '/' . ltrim($script_config['script_path'], '/'),
                        $script_config['dependencies'],
                        filemtime($script_path),
                        true
                    );
                }
            }
        }
    }

    /**
     * Output inline styles for used components
     */
    public function output_inline_styles() {
        if (empty($this->loaded_components)) {
            return;
        }

        $compiled_css = '';

        foreach ($this->loaded_components as $component_name) {
            if (isset($this->component_styles[$component_name])) {
                $style_config = $this->component_styles[$component_name];
                $style_path = get_template_directory() . '/' . ltrim($style_config['style_path'], '/');

                if (file_exists($style_path)) {
                    $css_content = $this->compile_scss_to_css($style_path);
                    if ($css_content) {
                        $compiled_css .= "/* Component: {$component_name} */\n" . $css_content . "\n\n";
                    }
                }
            }
        }

        if (!empty($compiled_css)) {
            echo "<style id='component-styles'>\n" . $compiled_css . "</style>\n";
        }
    }

    /**
     * Compile SCSS to CSS (basic implementation)
     * For production, consider using a proper SCSS compiler
     *
     * @param string $file_path Path to SCSS file
     * @return string Compiled CSS
     */
    private function compile_scss_to_css($file_path) {
        $content = file_get_contents($file_path);

        if (pathinfo($file_path, PATHINFO_EXTENSION) === 'scss') {
            // Basic SCSS to CSS conversion
            // For production, use proper SCSS compiler like ScssPhp
            $content = $this->basic_scss_conversion($content);
        }

        return $content;
    }

    /**
     * Basic SCSS to CSS conversion
     * This is a simplified version - for production use a proper SCSS compiler
     *
     * @param string $scss SCSS content
     * @return string CSS content
     */
    private function basic_scss_conversion($scss) {
        // Remove SCSS comments
        $scss = preg_replace('/\/\/.*$/m', '', $scss);

        // Handle nested selectors (very basic implementation)
        $scss = $this->flatten_nested_selectors($scss);

        // Handle variables (basic)
        $scss = $this->process_variables($scss);

        return $scss;
    }

    /**
     * Flatten nested selectors (basic implementation)
     */
    private function flatten_nested_selectors($scss) {
        // This is a very basic implementation
        // For production, use a proper SCSS parser
        return $scss;
    }

    /**
     * Process SCSS variables (basic implementation)
     */
    private function process_variables($scss) {
        // Extract variables
        preg_match_all('/\$([a-zA-Z0-9_-]+):\s*([^;]+);/', $scss, $matches);
        $variables = array_combine($matches[1], $matches[2]);

        // Replace variable usage
        foreach ($variables as $name => $value) {
            $scss = str_replace('$' . $name, $value, $scss);
        }

        // Remove variable declarations
        $scss = preg_replace('/\$[a-zA-Z0-9_-]+:\s*[^;]+;/', '', $scss);

        return $scss;
    }

    /**
     * Get list of loaded components
     *
     * @return array
     */
    public function get_loaded_components() {
        return $this->loaded_components;
    }

    /**
     * Clear loaded components (useful for testing)
     */
    public function clear_loaded_components() {
        $this->loaded_components = [];
    }
}

/**
 * Helper function to get component loader instance
 */
function get_component_loader() {
    return Component_Loader::getInstance();
}

/**
 * Helper function to register a component
 */
function register_component($component_name, $args = []) {
    return get_component_loader()->register_component($component_name, $args);
}

/**
 * Helper function to mark a component as used
 */
function use_component($component_name) {
    return get_component_loader()->use_component($component_name);
}
<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Component Loader - Unified component system for PHP auto-loading and style management
 *
 * This class provides automatic loading of component PHP files, styles and scripts
 * when components are rendered, similar to tree shaking in modern build tools.
 */
class Component_Loader {

    private static $instance = null;
    private $loaded_components = [];
    private $component_styles = [];
    private $component_scripts = [];
    private $component_php_files = [];
    private $php_components_loaded = false;

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

        // Auto-discover and load all PHP components
        $this->auto_load_php_components();
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
     * Compile SCSS to CSS or use pre-compiled CSS
     *
     * @param string $file_path Path to SCSS file
     * @return string CSS content
     */
    private function compile_scss_to_css($file_path) {
        // First, try to use pre-compiled CSS from build system
        if (pathinfo($file_path, PATHINFO_EXTENSION) === 'scss') {
            // Convert SCSS path to CSS path in dist directory
            $relative_path = str_replace(get_template_directory() . '/', '', $file_path);
            // Remove the 'src/' prefix from the relative path
            $css_relative_path = str_replace('src/', '', $relative_path);
            $css_path = get_template_directory() . '/dist/css/' . str_replace('.scss', '.css', $css_relative_path);

            if (file_exists($css_path)) {
                $content = file_get_contents($css_path);
                return $content;
            }
        }

        // Fallback: read the original file (CSS or SCSS)
        $content = file_get_contents($file_path);
        error_log("Using fallback SCSS compilation for: " . $file_path);

        // Only attempt basic SCSS conversion if it's SCSS and no pre-compiled version exists
        if (pathinfo($file_path, PATHINFO_EXTENSION) === 'scss') {
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

        // Handle variables first (before nested selectors)
        $scss = $this->process_variables($scss);

        // Handle nested selectors
        $scss = $this->flatten_nested_selectors($scss);

        return $scss;
    }

    /**
     * Flatten nested selectors (improved implementation)
     */
    private function flatten_nested_selectors($scss) {
        $lines = explode("\n", $scss);
        $output = [];
        $stack = [];
        $brace_stack = []; // Track opening braces
        $in_selector = false;
        $current_selector = '';
        $current_rules = [];

        foreach ($lines as $line) {
            $trimmed = trim($line);

            // Skip empty lines and comments
            if (empty($trimmed) || strpos($trimmed, '//') === 0) {
                if (!$in_selector) {
                    $output[] = $line;
                }
                continue;
            }

            // Check if this is a selector (contains { but not a property, or @media)
            if ((strpos($trimmed, '{') !== false && strpos($trimmed, ':') === false) || strpos($trimmed, '@media') === 0) {
                // Close previous selector if open
                if ($in_selector && !empty($current_rules)) {
                    $output[] = $current_selector . ' {';
                    $output = array_merge($output, $current_rules);
                    $output[] = '}';
                    $output[] = '';
                    $current_rules = [];
                }

                $selector = str_replace('{', '', $trimmed);

                // Handle @media queries - keep them as-is
                if (strpos($trimmed, '@media') === 0) {
                    $output[] = $trimmed . ' {';
                    $stack[] = trim($selector);
                    $brace_stack[] = 'media';
                    $in_selector = false;
                    continue;
                }

                // Handle nested selectors with &
                if (strpos($selector, '&') !== false) {
                    $parent = end($stack);
                    if ($parent && strpos($parent, '@media') === false) {
                        $selector = str_replace('&', $parent, $selector);
                    }
                } else if (!empty($stack)) {
                    // Child selector (only if parent is not @media)
                    $parent = end($stack);
                    if ($parent && strpos($parent, '@media') === false) {
                        $selector = $parent . ' ' . $selector;
                    }
                }

                $stack[] = trim($selector);
                $brace_stack[] = 'selector';
                $current_selector = trim($selector);
                $in_selector = true;
                continue;
            }

            // Check for closing brace
            if (strpos($trimmed, '}') !== false) {
                if ($in_selector && !empty($current_rules)) {
                    $output[] = $current_selector . ' {';
                    $output = array_merge($output, $current_rules);
                    $output[] = '}';
                    $current_rules = [];
                }

                // Close the brace
                $output[] = '}';

                // Pop from stacks
                array_pop($stack);
                $brace_type = array_pop($brace_stack);

                if ($brace_type === 'selector') {
                    $in_selector = false;
                }

                $output[] = '';
                continue;
            }

            // CSS property
            if ($in_selector && strpos($trimmed, ':') !== false) {
                $current_rules[] = '  ' . $trimmed;
            } else if (!$in_selector) {
                // Top-level CSS or CSS inside @media
                $output[] = $line;
            }
        }

        // Close any remaining open selector
        if ($in_selector && !empty($current_rules)) {
            $output[] = $current_selector . ' {';
            $output = array_merge($output, $current_rules);
            $output[] = '}';
        }

        // Close any remaining open braces
        while (!empty($brace_stack)) {
            $output[] = '}';
            array_pop($brace_stack);
        }

        return implode("\n", $output);
    }

    /**
     * Process SCSS variables (improved implementation)
     */
    private function process_variables($scss) {
        // Extract variables more carefully
        $variables = [];
        $lines = explode("\n", $scss);

        foreach ($lines as $line) {
            $trimmed = trim($line);
            if (preg_match('/^\$([a-zA-Z0-9_-]+):\s*([^;]+);/', $trimmed, $matches)) {
                $variables[$matches[1]] = trim($matches[2]);
            }
        }

        // Replace variable usage
        foreach ($variables as $name => $value) {
            $scss = str_replace('$' . $name, $value, $scss);
        }

        // Remove variable declarations
        $scss = preg_replace('/^\s*\$[a-zA-Z0-9_-]+:\s*[^;]+;\s*$/m', '', $scss);

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

    /**
     * Auto-discover and load all PHP components
     */
    private function auto_load_php_components() {
        if ($this->php_components_loaded) {
            return;
        }

        $theme_dir = get_stylesheet_directory();
        $components_base = $theme_dir . '/src/components';

        if (!is_dir($components_base)) {
            return;
        }

        $component_types = ['atoms', 'molecules'];

        foreach ($component_types as $type) {
            $type_dir = $components_base . '/' . $type;

            if (!is_dir($type_dir)) {
                continue;
            }

            // Get all component directories
            $component_dirs = glob($type_dir . '/*', GLOB_ONLYDIR);

            foreach ($component_dirs as $component_dir) {
                $this->load_component_from_directory($component_dir);
            }
        }

        $this->php_components_loaded = true;
    }

    /**
     * Load a component from its directory
     */
    private function load_component_from_directory($component_dir) {
        $component_name = basename($component_dir);

        // Look for the main PHP file
        $possible_files = [
            $component_dir . '/' . $component_name . '.php',
            $component_dir . '/' . str_replace('rio-', '', $component_name) . '.php',
        ];

        // Also check all PHP files in the directory
        $php_files = glob($component_dir . '/*.php');
        foreach ($php_files as $php_file) {
            $filename = basename($php_file, '.php');

            // Skip config, widget, and template files
            if (strpos($filename, '-config') !== false ||
                strpos($filename, '-widget') !== false ||
                strpos($filename, 'template') !== false) {
                continue;
            }

            $possible_files[] = $php_file;
        }

        // Load the first valid file found
        foreach ($possible_files as $file) {
            if (file_exists($file)) {
                require_once $file;
                $this->component_php_files[$component_name] = $file;
                break;
            }
        }
    }

    /**
     * Get list of loaded PHP component files
     */
    public function get_loaded_php_components() {
        return $this->component_php_files;
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
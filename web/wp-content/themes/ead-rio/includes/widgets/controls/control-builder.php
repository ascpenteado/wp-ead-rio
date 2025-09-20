<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Control Builder Class
 *
 * Builds Elementor controls from configuration arrays
 * Handles different control types and provides consistent API
 */
class Control_Builder {

    /**
     * Widget instance
     * @var \Elementor\Widget_Base
     */
    protected $widget;

    /**
     * Constructor
     *
     * @param \Elementor\Widget_Base $widget Widget instance
     */
    public function __construct(\Elementor\Widget_Base $widget) {
        $this->widget = $widget;
    }

    /**
     * Build a control section
     *
     * @param string $section_id Section identifier
     * @param array $section_config Section configuration
     */
    public function build_section($section_id, $section_config) {
        $section_args = [
            'label' => $section_config['label'] ?? ucfirst(str_replace('_', ' ', $section_id)),
            'tab' => $this->get_tab_constant($section_config['tab'] ?? 'content'),
        ];

        // Add conditional display if specified
        if (isset($section_config['condition'])) {
            $section_args['condition'] = $section_config['condition'];
        }

        $this->widget->start_controls_section($section_id, $section_args);

        // Build controls in this section
        if (isset($section_config['controls']) && is_array($section_config['controls'])) {
            foreach ($section_config['controls'] as $control_id => $control_config) {
                $this->build_control($control_id, $control_config);
            }
        }

        $this->widget->end_controls_section();
    }

    /**
     * Build a single control
     *
     * @param string $control_id Control identifier
     * @param array $control_config Control configuration
     */
    public function build_control($control_id, $control_config) {
        $control_type = $control_config['type'] ?? 'text';

        switch ($control_type) {
            case 'number':
                $this->build_number_control($control_id, $control_config);
                break;
            case 'select':
                $this->build_select_control($control_id, $control_config);
                break;
            case 'switcher':
                $this->build_switcher_control($control_id, $control_config);
                break;
            case 'slider':
                $this->build_slider_control($control_id, $control_config);
                break;
            case 'color':
                $this->build_color_control($control_id, $control_config);
                break;
            case 'dimensions':
                $this->build_dimensions_control($control_id, $control_config);
                break;
            case 'border':
                $this->build_border_control($control_id, $control_config);
                break;
            case 'box_shadow':
                $this->build_box_shadow_control($control_id, $control_config);
                break;
            case 'text':
            default:
                $this->build_text_control($control_id, $control_config);
                break;
        }
    }

    /**
     * Build number control
     */
    protected function build_number_control($control_id, $config) {
        $args = [
            'label' => $config['label'] ?? ucfirst(str_replace('_', ' ', $control_id)),
            'type' => \Elementor\Controls_Manager::NUMBER,
            'default' => $config['default'] ?? '',
        ];

        if (isset($config['min'])) $args['min'] = $config['min'];
        if (isset($config['max'])) $args['max'] = $config['max'];
        if (isset($config['step'])) $args['step'] = $config['step'];
        if (isset($config['condition'])) $args['condition'] = $config['condition'];

        $this->widget->add_control($control_id, $args);
    }

    /**
     * Build select control
     */
    protected function build_select_control($control_id, $config) {
        $args = [
            'label' => $config['label'] ?? ucfirst(str_replace('_', ' ', $control_id)),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => $config['default'] ?? '',
            'options' => $config['options'] ?? [],
        ];

        if (isset($config['condition'])) $args['condition'] = $config['condition'];

        $this->widget->add_control($control_id, $args);
    }

    /**
     * Build switcher control
     */
    protected function build_switcher_control($control_id, $config) {
        $args = [
            'label' => $config['label'] ?? ucfirst(str_replace('_', ' ', $control_id)),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => $config['label_on'] ?? __('Yes', 'ead-rio'),
            'label_off' => $config['label_off'] ?? __('No', 'ead-rio'),
            'return_value' => $config['return_value'] ?? 'yes',
            'default' => $config['default'] ?? '',
        ];

        if (isset($config['condition'])) $args['condition'] = $config['condition'];

        $this->widget->add_control($control_id, $args);
    }

    /**
     * Build slider control
     */
    protected function build_slider_control($control_id, $config) {
        $args = [
            'label' => $config['label'] ?? ucfirst(str_replace('_', ' ', $control_id)),
            'type' => \Elementor\Controls_Manager::SLIDER,
        ];

        if (isset($config['size_units'])) $args['size_units'] = $config['size_units'];
        if (isset($config['range'])) $args['range'] = $config['range'];
        if (isset($config['default'])) $args['default'] = $config['default'];
        if (isset($config['selectors'])) $args['selectors'] = $config['selectors'];
        if (isset($config['condition'])) $args['condition'] = $config['condition'];

        // Handle responsive control
        if (isset($config['responsive']) && $config['responsive']) {
            $this->widget->add_responsive_control($control_id, $args);
        } else {
            $this->widget->add_control($control_id, $args);
        }
    }

    /**
     * Build color control
     */
    protected function build_color_control($control_id, $config) {
        $args = [
            'label' => $config['label'] ?? ucfirst(str_replace('_', ' ', $control_id)),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => $config['default'] ?? '',
        ];

        if (isset($config['selectors'])) $args['selectors'] = $config['selectors'];
        if (isset($config['condition'])) $args['condition'] = $config['condition'];

        $this->widget->add_control($control_id, $args);
    }

    /**
     * Build dimensions control
     */
    protected function build_dimensions_control($control_id, $config) {
        $args = [
            'label' => $config['label'] ?? ucfirst(str_replace('_', ' ', $control_id)),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
        ];

        if (isset($config['size_units'])) $args['size_units'] = $config['size_units'];
        if (isset($config['selectors'])) $args['selectors'] = $config['selectors'];
        if (isset($config['condition'])) $args['condition'] = $config['condition'];

        // Handle responsive control
        if (isset($config['responsive']) && $config['responsive']) {
            $this->widget->add_responsive_control($control_id, $args);
        } else {
            $this->widget->add_control($control_id, $args);
        }
    }

    /**
     * Build border control (group control)
     */
    protected function build_border_control($control_id, $config) {
        $args = [
            'name' => $config['name'] ?? $control_id,
            'selector' => $config['selector'] ?? '',
        ];

        if (isset($config['condition'])) $args['condition'] = $config['condition'];

        $this->widget->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            $args
        );
    }

    /**
     * Build box shadow control (group control)
     */
    protected function build_box_shadow_control($control_id, $config) {
        $args = [
            'name' => $config['name'] ?? $control_id,
            'selector' => $config['selector'] ?? '',
        ];

        if (isset($config['condition'])) $args['condition'] = $config['condition'];

        $this->widget->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            $args
        );
    }

    /**
     * Build text control
     */
    protected function build_text_control($control_id, $config) {
        $args = [
            'label' => $config['label'] ?? ucfirst(str_replace('_', ' ', $control_id)),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => $config['default'] ?? '',
        ];

        if (isset($config['placeholder'])) $args['placeholder'] = $config['placeholder'];
        if (isset($config['condition'])) $args['condition'] = $config['condition'];

        $this->widget->add_control($control_id, $args);
    }

    /**
     * Get Elementor tab constant
     *
     * @param string $tab Tab name
     * @return string Tab constant
     */
    protected function get_tab_constant($tab) {
        switch ($tab) {
            case 'content':
                return \Elementor\Controls_Manager::TAB_CONTENT;
            case 'style':
                return \Elementor\Controls_Manager::TAB_STYLE;
            case 'advanced':
                return \Elementor\Controls_Manager::TAB_ADVANCED;
            default:
                return \Elementor\Controls_Manager::TAB_CONTENT;
        }
    }
}
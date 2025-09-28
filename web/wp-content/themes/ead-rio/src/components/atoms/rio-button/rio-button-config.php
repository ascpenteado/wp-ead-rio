<?php
/**
 * Rio Button Widget Configuration
 *
 * @package EAD_Rio
 */

if (!defined('ABSPATH')) {
    exit;
}

return [
    'name' => 'rio-button',
    'title' => __('Rio Button', 'ead-rio'),
    'description' => __('Customizable button component with various styles and options', 'ead-rio'),
    'icon' => 'fa fa-hand-pointer-o',
    'category' => 'ead-rio-widgets',
    'keywords' => ['button', 'link', 'cta', 'action'],
    'style_dependencies' => ['rio-button'],
    'template' => 'rio-button.template.php',

    'controls' => [
        // Content section
        'content_section' => [
            'type' => 'section',
            'label' => __('Content', 'ead-rio'),
            'tab' => 'content',
            'controls' => [
                'button_text' => [
                    'type' => 'text',
                    'label' => __('Button Text', 'ead-rio'),
                    'default' => __('Click Me', 'ead-rio'),
                    'placeholder' => __('Enter button text', 'ead-rio'),
                    'dynamic' => true
                ],
                'button_url' => [
                    'type' => 'url',
                    'label' => __('Button URL', 'ead-rio'),
                    'placeholder' => __('https://your-link.com', 'ead-rio'),
                    'default' => '#',
                    'dynamic' => true,
                    'show_external' => true
                ],
                'button_tag' => [
                    'type' => 'select',
                    'label' => __('HTML Tag', 'ead-rio'),
                    'default' => 'a',
                    'options' => [
                        'a' => __('Link (a)', 'ead-rio'),
                        'button' => __('Button', 'ead-rio')
                    ]
                ]
            ]
        ],

        // Style section
        'style_section' => [
            'type' => 'section',
            'label' => __('Style', 'ead-rio'),
            'tab' => 'content',
            'controls' => [
                'button_variant' => [
                    'type' => 'select',
                    'label' => __('Button Variant', 'ead-rio'),
                    'default' => 'primary',
                    'options' => [
                        'primary' => __('Primary', 'ead-rio'),
                        'secondary' => __('Secondary', 'ead-rio'),
                        'outline' => __('Outline', 'ead-rio')
                    ]
                ],
                'button_size' => [
                    'type' => 'select',
                    'label' => __('Button Size', 'ead-rio'),
                    'default' => 'medium',
                    'options' => [
                        'small' => __('Small', 'ead-rio'),
                        'medium' => __('Medium', 'ead-rio'),
                        'large' => __('Large', 'ead-rio')
                    ]
                ],
                'button_alignment' => [
                    'type' => 'choose',
                    'label' => __('Alignment', 'ead-rio'),
                    'default' => 'left',
                    'options' => [
                        'left' => [
                            'title' => __('Left', 'ead-rio'),
                            'icon' => 'eicon-text-align-left'
                        ],
                        'center' => [
                            'title' => __('Center', 'ead-rio'),
                            'icon' => 'eicon-text-align-center'
                        ],
                        'right' => [
                            'title' => __('Right', 'ead-rio'),
                            'icon' => 'eicon-text-align-right'
                        ]
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .rio-button-widget' => 'text-align: {{VALUE}};'
                    ]
                ]
            ]
        ],

        // Advanced section
        'advanced_section' => [
            'type' => 'section',
            'label' => __('Advanced', 'ead-rio'),
            'tab' => 'content',
            'controls' => [
                'button_id' => [
                    'type' => 'text',
                    'label' => __('Button ID', 'ead-rio'),
                    'placeholder' => __('my-button-id', 'ead-rio'),
                    'description' => __('Add a unique ID for the button element', 'ead-rio')
                ],
                'css_classes' => [
                    'type' => 'text',
                    'label' => __('CSS Classes', 'ead-rio'),
                    'placeholder' => __('custom-class another-class', 'ead-rio'),
                    'description' => __('Add custom CSS classes separated by spaces', 'ead-rio')
                ],
                'enable_js_options' => [
                    'type' => 'switcher',
                    'label' => __('Enable JavaScript Options', 'ead-rio'),
                    'default' => 'no',
                    'description' => __('Enable advanced JavaScript functionality for the button', 'ead-rio')
                ],
                'disable_on_click' => [
                    'type' => 'switcher',
                    'label' => __('Disable on Click', 'ead-rio'),
                    'default' => 'no',
                    'condition' => [
                        'enable_js_options' => 'yes'
                    ]
                ],
                'loading_text' => [
                    'type' => 'text',
                    'label' => __('Loading Text', 'ead-rio'),
                    'placeholder' => __('Loading...', 'ead-rio'),
                    'condition' => [
                        'enable_js_options' => 'yes',
                        'disable_on_click' => 'yes'
                    ]
                ]
            ]
        ]
    ]
];
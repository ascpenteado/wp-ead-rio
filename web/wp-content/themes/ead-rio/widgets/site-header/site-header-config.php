<?php
/**
 * Site Header Widget Configuration
 *
 * @package EAD_Rio
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

return [
    'name' => 'site-header',
    'title' => __('Site Header', 'ead-rio'),
    'description' => __('A customizable site header with logo, navigation, and CTA button', 'ead-rio'),
    'icon' => 'eicon-header',
    'category' => 'ead-rio-layout',
    'keywords' => ['header', 'navigation', 'menu', 'logo', 'site'],
    'version' => '1.0.0',
    'dependencies' => [
        'elementor' => '3.0.0',
    ],
    'assets' => [
        'styles' => [
            'site-header-widget' => [
                'src' => '/widgets/site-header/site-header.scss',
                'deps' => [],
                'version' => '1.0.0',
            ],
        ],
        'scripts' => [
            'site-header-widget' => [
                'src' => '/widgets/site-header/site-header.js',
                'deps' => ['jquery'],
                'version' => '1.0.0',
                'in_footer' => true,
            ],
        ],
    ],
    'features' => [
        'responsive' => true,
        'sticky' => true,
        'mobile_menu' => true,
        'custom_logo' => true,
        'navigation_menu' => true,
        'cta_button' => true,
        'search_form' => true,
    ],
    'default_settings' => [
        'show_logo' => 'yes',
        'show_site_title' => 'yes',
        'show_tagline' => 'no',
        'show_cta' => 'yes',
        'show_mobile_menu' => 'yes',
        'menu_location' => 'primary',
        'cta_text' => __('Get Started', 'ead-rio'),
        'logo_size' => [
            'unit' => 'px',
            'size' => 50,
        ],
    ],
    'style_presets' => [
        'default' => [
            'label' => __('Default', 'ead-rio'),
            'settings' => [
                'header_background' => 'rgba(255, 255, 255, 0.95)',
                'header_shadow' => '0 2px 20px rgba(0, 0, 0, 0.1)',
            ],
        ],
        'dark' => [
            'label' => __('Dark', 'ead-rio'),
            'settings' => [
                'header_background' => 'rgba(33, 37, 41, 0.95)',
                'menu_item_color' => 'rgba(255, 255, 255, 0.87)',
                'site_title_color' => '#ffffff',
            ],
        ],
        'transparent' => [
            'label' => __('Transparent', 'ead-rio'),
            'settings' => [
                'header_background' => 'transparent',
                'header_shadow' => 'none',
            ],
        ],
    ],
    'responsive_breakpoints' => [
        'mobile' => 768,
        'tablet' => 1024,
        'desktop' => 1200,
    ],
];
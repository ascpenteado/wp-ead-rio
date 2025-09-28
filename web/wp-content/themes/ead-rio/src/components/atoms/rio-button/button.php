<?php
/**
 * Button Atom Component
 *
 * @package EAD_Rio
 */

if (!defined('ABSPATH')) {
    exit;
}

// Auto-register component styles
if (!function_exists('get_component_loader')) {
    require_once get_stylesheet_directory() . '/src/includes/component-loader.php';
}

// Register this component's styles
register_component('rio-button', [
    'style_path' => 'src/components/atoms/rio-button/button.scss',
    'dependencies' => []
]);

/**
 * Button Component
 *
 * @param array $args {
 *     @type string $text        Button text
 *     @type string $url         Button URL/href
 *     @type string $variant     Button variant: 'primary', 'secondary', 'outline' (default: 'primary')
 *     @type string $size        Button size: 'small', 'medium', 'large' (default: 'medium')
 *     @type string $tag         HTML tag: 'a', 'button' (default: 'a')
 *     @type array  $attributes  Additional HTML attributes
 *     @type string $classes     Additional CSS classes
 * }
 */
function rio_button($args = []) {
    // Mark component as used so styles will be loaded
    use_component('rio-button');

    $defaults = [
        'text' => 'Button',
        'url' => '#',
        'variant' => 'primary',
        'size' => 'medium',
        'tag' => 'a',
        'attributes' => [],
        'classes' => '',
    ];

    $args = wp_parse_args($args, $defaults);

    // Validate variant
    $allowed_variants = ['primary', 'secondary', 'outline'];
    if (!in_array($args['variant'], $allowed_variants)) {
        $args['variant'] = 'primary';
    }

    // Validate size
    $allowed_sizes = ['small', 'medium', 'large'];
    if (!in_array($args['size'], $allowed_sizes)) {
        $args['size'] = 'medium';
    }

    // Validate tag
    $allowed_tags = ['a', 'button'];
    if (!in_array($args['tag'], $allowed_tags)) {
        $args['tag'] = 'a';
    }

    // Build CSS classes
    $css_classes = [
        'btn',
        'btn--' . $args['variant'],
        'btn--' . $args['size']
    ];

    if (!empty($args['classes'])) {
        $css_classes[] = $args['classes'];
    }

    $class_string = implode(' ', $css_classes);

    // Build attributes
    $attributes = $args['attributes'];
    $attributes['class'] = $class_string;

    // Add href for anchor tags
    if ($args['tag'] === 'a' && !empty($args['url'])) {
        $attributes['href'] = esc_url($args['url']);
    }

    // Build attribute string
    $attr_string = '';
    foreach ($attributes as $key => $value) {
        if ($value !== null && $value !== '') {
            $attr_string .= sprintf(' %s="%s"', esc_attr($key), esc_attr($value));
        }
    }

    // Render button
    printf(
        '<%1$s%2$s>%3$s</%1$s>',
        $args['tag'],
        $attr_string,
        esc_html($args['text'])
    );
}
<?php
/**
 * Rio Button Widget Template
 *
 * Template for rendering the button widget
 *
 * Available variables:
 * @var array $button_args - Button arguments for the rio_button function
 * @var array $settings    - Widget settings from Elementor
 */

if (!defined('ABSPATH')) {
    exit;
}

// Include the button component
require_once get_stylesheet_directory() . '/src/components/atoms/rio-button/button.php';
?>

<div class="rio-button-widget">
    <?php rio_button($button_args); ?>
</div>
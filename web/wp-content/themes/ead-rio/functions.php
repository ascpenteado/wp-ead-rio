<?php
/**
 * EAD Rio Theme Functions
 *
 * @package EAD_Rio
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Include all theme modules from dist (production build output)
require_once __DIR__ . '/dist/php/theme-setup.php';
require_once __DIR__ . '/dist/php/enqueue.php';
require_once __DIR__ . '/dist/php/widgets.php';
require_once __DIR__ . '/dist/php/elementor.php';
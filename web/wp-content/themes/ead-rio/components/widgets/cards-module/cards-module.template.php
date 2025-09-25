<?php
/**
 * Cards Module Template
 *
 * Template for rendering the cards module widget
 *
 * Available variables:
 * @var WP_Query $posts         - WordPress query object with course posts
 * @var array    $settings      - Widget settings from Elementor
 * @var string   $columns       - Number of columns for grid layout
 */

if (!defined('ABSPATH')) {
    exit;
}

// Include the course card component
require_once get_stylesheet_directory() . '/components/molecules/course-card.php';
?>

<div class="cards-module">
    <div class="cards-module__grid cards-module__grid--columns-<?php echo esc_attr($columns); ?>">
        <?php while ($posts->have_posts()) : $posts->the_post();
            render_course_card([
                'post_id' => get_the_ID(),
                'show_short_description' => 'yes' === $settings['show_short_description'],
                'show_instituicao' => 'yes' === $settings['show_instituicao'],
                'show_course_area' => 'yes' === $settings['show_course_area'],
                'show_course_degree' => 'yes' === $settings['show_course_degree'],
            ]);
        endwhile; ?>
    </div>
</div>
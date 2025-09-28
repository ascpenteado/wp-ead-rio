<?php

if (!defined('ABSPATH')) {
    exit;
}

// Auto-register component styles when component loader is available
if (!function_exists('get_component_loader')) {
    require_once get_stylesheet_directory() . '/src/includes/component-loader.php';
}

/**
 * Course Card Component
 *
 * @param array $args {
 *     @type int    $post_id                 The course post ID
 *     @type bool   $show_short_description  Whether to show short description
 *     @type bool   $show_instituicao        Whether to show institution
 *     @type bool   $show_course_area        Whether to show course area
 *     @type bool   $show_course_degree      Whether to show course degree
 * }
 */
function rio_course_card($args = []) {
    // Register this component's styles
    register_component('rio-course-card', [
        'style_path' => 'src/components/molecules/rio-course-card.scss',
        'dependencies' => []
    ]);

    // Mark component as used so styles will be loaded
    use_component('rio-course-card');
    $defaults = [
        'post_id' => get_the_ID(),
        'show_short_description' => true,
        'show_instituicao' => true,
        'show_course_area' => true,
        'show_course_degree' => true,
    ];

    $args = wp_parse_args($args, $defaults);

    $post_id = $args['post_id'];
    $course_title = get_the_title($post_id);
    $course_permalink = get_permalink($post_id);

    // Skip if no title
    if (empty(trim($course_title))) {
        return;
    }

    // Get actual course fields
    $course_name = get_field('course_name', $post_id) ?: get_post_meta($post_id, 'course_name', true);
    $short_description = get_field('short_description', $post_id) ?: get_post_meta($post_id, 'short_description', true);

    // These fields appear to be relationship/taxonomy fields that return IDs
    $instituicao_raw = get_field('instituicao', $post_id) ?: get_post_meta($post_id, 'instituicao', true);
    $course_area_raw = get_field('course_area', $post_id) ?: get_post_meta($post_id, 'course_area', true);
    $course_degree_raw = get_field('course_degree', $post_id) ?: get_post_meta($post_id, 'course_degree', true);

    // Helper function to extract display values from relationship/taxonomy fields
    $get_field_display_value = function($field_value) {
        if (empty($field_value)) {
            return '';
        }

        // If it's a string, return it
        if (is_string($field_value)) {
            return trim($field_value);
        }

        // If it's an array of IDs, try to get the display values
        if (is_array($field_value)) {
            $display_values = [];

            foreach ($field_value as $id) {
                // Try as taxonomy term
                if (is_numeric($id)) {
                    $term = get_term($id);
                    if ($term && !is_wp_error($term)) {
                        $display_values[] = $term->name;
                        continue;
                    }

                    // Try as post
                    $post = get_post($id);
                    if ($post) {
                        $display_values[] = $post->post_title;
                        continue;
                    }
                }

                // If it's already a string, use it
                if (is_string($id) && !empty(trim($id))) {
                    $display_values[] = trim($id);
                }
            }

            return implode(', ', $display_values);
        }

        return '';
    };

    // Clean and validate data
    $course_name = is_string($course_name) ? trim($course_name) : '';
    $instituicao = $get_field_display_value($instituicao_raw);
    $course_area = $get_field_display_value($course_area_raw);
    $course_degree = $get_field_display_value($course_degree_raw);
    $short_description = is_string($short_description) ? trim($short_description) : '';

    // Use course_name if available, otherwise fall back to post title
    $display_title = !empty($course_name) ? $course_name : $course_title;
    ?>

    <div class="course-card">
        <div class="course-card__image">
            <a href="<?php echo esc_url($course_permalink); ?>">
                <?php if (has_post_thumbnail($post_id)) : ?>
                    <?php echo get_the_post_thumbnail($post_id, 'medium', ['class' => 'course-card__thumbnail', 'alt' => esc_attr($course_title)]); ?>
                <?php else : ?>
                    <div class="course-card__placeholder">
                        <svg width="80" height="80" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 2L13.09 8.26L22 9L13.09 9.74L12 16L10.91 9.74L2 9L10.91 8.26L12 2Z" fill="currentColor"/>
                            <path d="M12 7C14.21 7 16 8.79 16 11C16 13.21 14.21 15 12 15C9.79 15 8 13.21 8 11C8 8.79 9.79 7 12 7Z" fill="currentColor" opacity="0.5"/>
                        </svg>
                        <span>Curso</span>
                    </div>
                <?php endif; ?>
            </a>
        </div>

        <div class="course-card__content">
            <?php if ($args['show_course_degree'] && $course_degree) : ?>
                <div class="course-card__degree">
                    <span class="course-card__degree-badge"><?php echo esc_html($course_degree); ?></span>
                </div>
            <?php endif; ?>

            <h3 class="course-card__title">
                <a href="<?php echo esc_url($course_permalink); ?>" class="course-card__title-link"><?php echo esc_html($display_title); ?></a>
            </h3>

            <?php
            // Check if we have any meta to display
            $has_meta = ($args['show_instituicao'] && $instituicao) ||
                       ($args['show_course_area'] && $course_area) ||
                       ($args['show_course_degree'] && $course_degree);
            ?>

            <?php if ($has_meta) : ?>
                <div class="course-card__meta">
                    <?php if ($args['show_instituicao'] && $instituicao) : ?>
                        <div class="course-card__meta-item">
                            <span class="course-card__meta-label">Instituição:</span>
                            <span class="course-card__meta-value course-card__meta-value--institution"><?php echo esc_html($instituicao); ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if ($args['show_course_area'] && $course_area) : ?>
                        <div class="course-card__meta-item">
                            <span class="course-card__meta-label">Área:</span>
                            <span class="course-card__meta-value"><?php echo esc_html($course_area); ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <?php if ($args['show_short_description'] && !empty($short_description)) : ?>
                <div class="course-card__excerpt">
                    <?php echo wp_trim_words($short_description, 20, '...'); ?>
                </div>
            <?php endif; ?>

            <div class="course-card__actions">
                <a href="<?php echo esc_url($course_permalink); ?>" class="course-card__button">
                    Ver Curso
                </a>
            </div>
        </div>
    </div>

    <?php
}
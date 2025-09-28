<?php
/**
 * Single Curso Template
 *
 * @package EAD_Rio
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<div class="curso-single-container">
    <?php while (have_posts()) : the_post(); ?>
        <article class="curso-single">
            <!-- Hero Section -->
            <div class="curso-hero">
                <div class="curso-hero-image">
                    <?php if (has_post_thumbnail()) : ?>
                        <?php the_post_thumbnail('large', ['class' => 'curso-featured-image', 'alt' => get_the_title()]); ?>
                    <?php else : ?>
                        <div class="curso-hero-placeholder">
                            <svg width="120" height="120" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 2L13.09 8.26L22 9L13.09 9.74L12 16L10.91 9.74L2 9L10.91 8.26L12 2Z" fill="currentColor"/>
                                <path d="M12 7C14.21 7 16 8.79 16 11C16 13.21 14.21 15 12 15C9.79 15 8 13.21 8 11C8 8.79 9.79 7 12 7Z" fill="currentColor" opacity="0.5"/>
                            </svg>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="curso-hero-content">
                    <?php
                    // Get course fields
                    $course_name = get_field('course_name') ?: get_post_meta(get_the_ID(), 'course_name', true);
                    $instituicao_raw = get_field('instituicao') ?: get_post_meta(get_the_ID(), 'instituicao', true);
                    $course_area_raw = get_field('course_area') ?: get_post_meta(get_the_ID(), 'course_area', true);
                    $course_degree_raw = get_field('course_degree') ?: get_post_meta(get_the_ID(), 'course_degree', true);
                    $short_description = get_field('short_description') ?: get_post_meta(get_the_ID(), 'short_description', true);
                    $description = get_field('description') ?: get_post_meta(get_the_ID(), 'description', true);

                    // Helper function to extract display values from relationship/taxonomy fields
                    $get_field_display_value = function($field_value) {
                        if (empty($field_value)) {
                            return '';
                        }

                        if (is_string($field_value)) {
                            return trim($field_value);
                        }

                        if (is_array($field_value)) {
                            $display_values = [];

                            foreach ($field_value as $id) {
                                if (is_numeric($id)) {
                                    $term = get_term($id);
                                    if ($term && !is_wp_error($term)) {
                                        $display_values[] = $term->name;
                                        continue;
                                    }

                                    $post = get_post($id);
                                    if ($post) {
                                        $display_values[] = $post->post_title;
                                        continue;
                                    }
                                }

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
                    $description = is_string($description) ? trim($description) : '';

                    // Use course_name if available, otherwise fall back to post title
                    $display_title = !empty($course_name) ? $course_name : get_the_title();
                    ?>

                    <h1 class="curso-title"><?php echo esc_html($display_title); ?></h1>

                    <?php if (!empty($short_description)) : ?>
                        <div class="curso-short-description">
                            <?php echo wp_kses_post($short_description); ?>
                        </div>
                    <?php endif; ?>

                    <div class="curso-meta-badges">
                        <?php if (!empty($instituicao)) : ?>
                            <div class="curso-badge instituicao-badge">
                                <span class="badge-icon">🏛️</span>
                                <span class="badge-content">
                                    <span class="badge-label">Instituição</span>
                                    <span class="badge-value"><?php echo esc_html($instituicao); ?></span>
                                </span>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($course_area)) : ?>
                            <div class="curso-badge area-badge">
                                <span class="badge-icon">📚</span>
                                <span class="badge-content">
                                    <span class="badge-label">Área</span>
                                    <span class="badge-value"><?php echo esc_html($course_area); ?></span>
                                </span>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($course_degree)) : ?>
                            <div class="curso-badge degree-badge">
                                <span class="badge-icon">🎓</span>
                                <span class="badge-content">
                                    <span class="badge-label">Grau</span>
                                    <span class="badge-value"><?php echo esc_html($course_degree); ?></span>
                                </span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="curso-actions">
                        <a href="#quero-bolsa" class="btn-inscricao">
                            Quero Minha Bolsa
                        </a>
                        <a href="#curso-detalhes" class="btn-detalhes">
                            Ver Detalhes
                        </a>
                    </div>
                </div>
            </div>

            <!-- Course Details Section -->
            <?php if (!empty($description)) : ?>
                <div class="curso-details" id="curso-detalhes">
                    <div class="container">
                        <h2 class="section-title">Sobre o Curso</h2>
                        <div class="curso-description">
                            <?php echo wp_kses_post(wpautop($description)); ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- CTA Section -->
            <div class="curso-cta-section" id="quero-bolsa">
                <div class="container">
                    <h2 class="cta-title">Interessado neste curso?</h2>
                    <p class="cta-subtitle">Solicite mais informações e garanta sua vaga!</p>
                    <?php echo do_shortcode('[contact-form-7 id="4fbd2a5"]'); ?>
                </div>
            </div>
        </article>
    <?php endwhile; ?>
</div>

<?php
get_footer();
?>
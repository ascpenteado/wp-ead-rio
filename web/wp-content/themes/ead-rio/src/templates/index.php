<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @package EAD_Rio
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="container">

        <?php if (have_posts()) : ?>

            <?php if (is_home() && !is_front_page()) : ?>
                <header class="page-header">
                    <h1 class="page-title"><?php single_post_title(); ?></h1>
                </header>
            <?php endif; ?>

            <div class="posts-container">
                <?php while (have_posts()) : the_post(); ?>

                    <article id="post-<?php the_ID(); ?>" <?php post_class('post-item'); ?>>

                        <?php if (has_post_thumbnail()) : ?>
                            <div class="post-thumbnail">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail('medium'); ?>
                                </a>
                            </div>
                        <?php endif; ?>

                        <div class="post-content">
                            <header class="entry-header">
                                <?php
                                if (is_singular()) :
                                    the_title('<h1 class="entry-title">', '</h1>');
                                else :
                                    the_title('<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>');
                                endif;
                                ?>

                                <div class="entry-meta">
                                    <span class="posted-on">
                                        <time class="entry-date published" datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                                            <?php echo esc_html(get_the_date()); ?>
                                        </time>
                                    </span>
                                    <span class="byline">
                                        <?php esc_html_e('by', 'ead-rio'); ?>
                                        <span class="author vcard">
                                            <a class="url fn n" href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
                                                <?php echo esc_html(get_the_author()); ?>
                                            </a>
                                        </span>
                                    </span>
                                </div>
                            </header>

                            <div class="entry-summary">
                                <?php the_excerpt(); ?>
                            </div>

                            <footer class="entry-footer">
                                <a href="<?php the_permalink(); ?>" class="read-more">
                                    <?php esc_html_e('Read More', 'ead-rio'); ?>
                                </a>
                            </footer>
                        </div>
                    </article>

                <?php endwhile; ?>
            </div>

            <?php
            // Pagination
            the_posts_pagination(array(
                'mid_size'  => 2,
                'prev_text' => __('Previous', 'ead-rio'),
                'next_text' => __('Next', 'ead-rio'),
            ));
            ?>

        <?php else : ?>

            <section class="no-results not-found">
                <header class="page-header">
                    <h1 class="page-title"><?php esc_html_e('Nothing here', 'ead-rio'); ?></h1>
                </header>

                <div class="page-content">
                    <?php if (is_home() && current_user_can('publish_posts')) : ?>

                        <p>
                            <?php
                            printf(
                                wp_kses(
                                    __('Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'ead-rio'),
                                    array(
                                        'a' => array(
                                            'href' => array(),
                                        ),
                                    )
                                ),
                                esc_url(admin_url('post-new.php'))
                            );
                            ?>
                        </p>

                    <?php elseif (is_search()) : ?>

                        <p><?php esc_html_e('Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'ead-rio'); ?></p>
                        <?php get_search_form(); ?>

                    <?php else : ?>

                        <p><?php esc_html_e('It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'ead-rio'); ?></p>
                        <?php get_search_form(); ?>

                    <?php endif; ?>
                </div>
            </section>

        <?php endif; ?>

    </div>
</main>

<?php
get_sidebar();
get_footer();
?>
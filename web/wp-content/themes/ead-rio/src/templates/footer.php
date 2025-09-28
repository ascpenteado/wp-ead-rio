<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @package EAD_Rio
 */

?>

    </div><!-- #content -->

    <footer id="colophon" class="site-footer">
        <?php if (is_active_sidebar('footer-1')) : ?>
            <div class="footer-widgets">
                <div class="container">
                    <?php dynamic_sidebar('footer-1'); ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="site-info">
            <div class="container">
                <div class="footer-content">
                    <div class="footer-text">
                        <a href="<?php echo esc_url(__('https://wordpress.org/', 'ead-rio')); ?>">
                            <?php
                            /* translators: %s: CMS name, i.e. WordPress. */
                            printf(esc_html__('Proudly powered by %s', 'ead-rio'), 'WordPress');
                            ?>
                        </a>
                        <span class="sep"> | </span>
                        <?php
                        /* translators: 1: Theme name, 2: Theme author. */
                        printf(esc_html__('Theme: %1$s by %2$s.', 'ead-rio'), 'EAD Rio', '<a href="#">EAD Rio Team</a>');
                        ?>
                    </div>

                    <?php if (has_nav_menu('footer')) : ?>
                        <nav class="footer-navigation">
                            <?php
                            wp_nav_menu(
                                array(
                                    'theme_location' => 'footer',
                                    'menu_id'        => 'footer-menu',
                                    'container'      => false,
                                    'menu_class'     => 'footer-nav-menu',
                                    'depth'          => 1,
                                )
                            );
                            ?>
                        </nav>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </footer>
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
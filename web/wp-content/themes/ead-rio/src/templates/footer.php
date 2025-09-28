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
                        <p>
                            CNPJ: 00.000.000/0000-00<br>
                            © <?php echo date('Y'); ?> EAD Rio - Todos os direitos reservados.
                        </p>
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
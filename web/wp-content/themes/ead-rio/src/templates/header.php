<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @package EAD_Rio
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
    <a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e('Skip to content', 'ead-rio'); ?></a>

    <header id="masthead" class="site-header">
        <div class="container">
            <div class="site-branding"><?php the_custom_logo(); ?></div>

            <nav id="site-navigation" class="main-navigation">
                <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
                    <?php esc_html_e('Primary Menu', 'ead-rio'); ?>
                </button>
                <?php
                wp_nav_menu(
                    array(
                        'theme_location' => 'primary',
                        'menu_id'        => 'primary-menu',
                        'container'      => false,
                        'menu_class'     => 'nav-menu',
                    )
                );
                ?>
            </nav>

            <?php
            // Render CTA button (auto-loaded globally)
            rio_button([
                'text' => 'Matricule-se',
                'url' => '/matricula',
                'variant' => 'primary',
                'size' => 'medium'
            ]);
            ?>
        </div>
    </header>

    <div id="content" class="site-content">
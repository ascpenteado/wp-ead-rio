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

    <header id="masthead" class="header">
        <div class="header__container">
            <div class="header__branding">
                <?php the_custom_logo(); ?>
            </div>

            
            <nav id="site-navigation" class="header__navigation">
                <?php
                wp_nav_menu(
                    array(
                        'theme_location' => 'primary',
                        'menu_id'        => 'primary-menu',
                        'container'      => false,
                        'menu_class'     => 'header__nav-menu',
                    )
                );
                ?>
            </nav>

            <div class="header__menu-toggle-wrapper">
                <?php
            // Render CTA button (auto-loaded globally)
            rio_button([
                'text' => 'Matricule-se',
                'url' => '#quero-bolsa',
                'variant' => 'primary',
                'size' => 'medium',
                'classes' => 'header__cta-button'
            ]);
            ?>

                <button class="header__menu-toggle" aria-controls="primary-menu" aria-expanded="false">
                    <span class="header__menu-toggle-text"><?php esc_html_e('Primary Menu', 'ead-rio'); ?></span>
                </button>
            
            </div>

        </div>
    </header>

    <div id="content" class="site-content">
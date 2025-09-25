<?php
/**
 * Site Header Widget Template
 *
 * @package EAD_Rio
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

$logo_url = '';
if (!empty($settings['custom_logo']['url'])) {
    $logo_url = $settings['custom_logo']['url'];
} elseif (has_custom_logo()) {
    $custom_logo_id = get_theme_mod('custom_logo');
    $logo_url = wp_get_attachment_image_url($custom_logo_id, 'full');
}

$site_title = !empty($settings['custom_site_title']) ? $settings['custom_site_title'] : get_bloginfo('name');
$site_description = get_bloginfo('description');

$menu_location = $settings['menu_location'] ?? 'primary';
$cta_link = $settings['cta_link'] ?? ['url' => '#'];
$cta_text = $settings['cta_text'] ?? __('Get Started', 'ead-rio');

?>

<header class="site-header site-header-widget" data-elementor-type="widget">
    <div class="site-header__container">

        <?php if ($settings['show_logo'] === 'yes' || $settings['show_site_title'] === 'yes') : ?>
        <div class="site-header__branding">
            <?php if ($settings['show_logo'] === 'yes' && $logo_url) : ?>
                <a href="<?php echo esc_url(home_url('/')); ?>" class="custom-logo-link" rel="home">
                    <img src="<?php echo esc_url($logo_url); ?>"
                         alt="<?php echo esc_attr($site_title); ?>"
                         style="height: <?php echo esc_attr($settings['logo_size']['size'] ?? 50); ?>px;">
                </a>
            <?php endif; ?>

            <?php if ($settings['show_site_title'] === 'yes') : ?>
                <?php if (is_front_page() && is_home()) : ?>
                    <h1 class="site-title">
                        <a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                            <?php echo esc_html($site_title); ?>
                        </a>
                    </h1>
                <?php else : ?>
                    <p class="site-title">
                        <a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                            <?php echo esc_html($site_title); ?>
                        </a>
                    </p>
                <?php endif; ?>
            <?php endif; ?>

            <?php if ($settings['show_tagline'] === 'yes' && $site_description) : ?>
                <p class="site-description"><?php echo esc_html($site_description); ?></p>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <nav class="site-header__navigation" id="site-navigation">
            <?php
            // Check if menu_location is numeric (menu ID) or string (menu location)
            if (is_numeric($menu_location)) {
                wp_nav_menu([
                    'menu' => $menu_location,
                    'menu_id' => 'primary-menu',
                    'container' => false,
                    'menu_class' => 'nav-menu',
                    'fallback_cb' => [$this, 'fallback_menu'],
                ]);
            } else {
                wp_nav_menu([
                    'theme_location' => $menu_location,
                    'menu_id' => 'primary-menu',
                    'container' => false,
                    'menu_class' => 'nav-menu',
                    'fallback_cb' => [$this, 'fallback_menu'],
                ]);
            }
            ?>
        </nav>

        <?php if ($settings['show_cta'] === 'yes') : ?>
        <div class="site-header__cta">
            <a href="<?php echo esc_url($cta_link['url']); ?>"
               class="btn-primary"
               <?php echo $cta_link['is_external'] ? 'target="_blank"' : ''; ?>
               <?php echo $cta_link['nofollow'] ? 'rel="nofollow"' : ''; ?>>
                <?php echo esc_html($cta_text); ?>
            </a>
        </div>
        <?php endif; ?>

        <?php if ($settings['show_mobile_menu'] === 'yes') : ?>
        <button class="site-header__menu-toggle" aria-controls="primary-menu" aria-expanded="false">
            <span></span>
            <span></span>
            <span></span>
            <span class="menu-toggle-text"><?php esc_html_e('Menu', 'ead-rio'); ?></span>
        </button>
        <?php endif; ?>

    </div>
</header>

<?php
// Fallback menu function
if (!function_exists('site_header_fallback_menu')) {
    function site_header_fallback_menu() {
        echo '<ul class="nav-menu">';
        echo '<li><a href="' . esc_url(home_url('/')) . '">' . esc_html__('Home', 'ead-rio') . '</a></li>';

        // Get pages for fallback menu
        $pages = get_pages([
            'sort_order' => 'ASC',
            'sort_column' => 'menu_order',
            'number' => 5,
        ]);

        foreach ($pages as $page) {
            echo '<li><a href="' . esc_url(get_permalink($page->ID)) . '">' . esc_html($page->post_title) . '</a></li>';
        }

        echo '</ul>';
    }
}
?>
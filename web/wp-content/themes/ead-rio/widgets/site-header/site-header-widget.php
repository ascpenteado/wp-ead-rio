<?php
/**
 * Site Header Elementor Widget
 *
 * @package EAD_Rio
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;

class Site_Header_Widget extends Widget_Base {

    public function get_name() {
        return 'site-header';
    }

    public function get_title() {
        return __('Site Header', 'ead-rio');
    }

    public function get_icon() {
        return 'eicon-header';
    }

    public function get_categories() {
        return ['ead-rio-widgets'];
    }

    public function get_keywords() {
        return ['header', 'navigation', 'menu', 'logo', 'site'];
    }

    public function get_style_depends() {
        return ['site-header-widget'];
    }

    public function get_script_depends() {
        return ['site-header-widget'];
    }

    protected function register_controls() {

        // Logo Section
        $this->start_controls_section(
            'logo_section',
            [
                'label' => __('Logo & Branding', 'ead-rio'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_logo',
            [
                'label' => __('Show Logo', 'ead-rio'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'ead-rio'),
                'label_off' => __('Hide', 'ead-rio'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'custom_logo',
            [
                'label' => __('Custom Logo', 'ead-rio'),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => '',
                ],
                'condition' => [
                    'show_logo' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'logo_size',
            [
                'label' => __('Logo Size', 'ead-rio'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 20,
                        'max' => 200,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 50,
                ],
                'condition' => [
                    'show_logo' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'show_site_title',
            [
                'label' => __('Show Site Title', 'ead-rio'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'ead-rio'),
                'label_off' => __('Hide', 'ead-rio'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'custom_site_title',
            [
                'label' => __('Custom Site Title', 'ead-rio'),
                'type' => Controls_Manager::TEXT,
                'default' => '',
                'placeholder' => __('Leave empty to use site title', 'ead-rio'),
                'condition' => [
                    'show_site_title' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'show_tagline',
            [
                'label' => __('Show Tagline', 'ead-rio'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'ead-rio'),
                'label_off' => __('Hide', 'ead-rio'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

        $this->end_controls_section();

        // Navigation Section
        $this->start_controls_section(
            'navigation_section',
            [
                'label' => __('Navigation', 'ead-rio'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'menu_location',
            [
                'label' => __('Menu Location', 'ead-rio'),
                'type' => Controls_Manager::SELECT,
                'default' => 'primary',
                'options' => $this->get_available_menus(),
            ]
        );

        $this->add_control(
            'show_mobile_menu',
            [
                'label' => __('Show Mobile Menu', 'ead-rio'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'ead-rio'),
                'label_off' => __('Hide', 'ead-rio'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

        // CTA Section
        $this->start_controls_section(
            'cta_section',
            [
                'label' => __('Call to Action', 'ead-rio'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'show_cta',
            [
                'label' => __('Show CTA Button', 'ead-rio'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'ead-rio'),
                'label_off' => __('Hide', 'ead-rio'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'cta_text',
            [
                'label' => __('CTA Text', 'ead-rio'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Get Started', 'ead-rio'),
                'condition' => [
                    'show_cta' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'cta_link',
            [
                'label' => __('CTA Link', 'ead-rio'),
                'type' => Controls_Manager::URL,
                'placeholder' => __('https://your-link.com', 'ead-rio'),
                'default' => [
                    'url' => '#',
                ],
                'condition' => [
                    'show_cta' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Controls
        $this->start_controls_section(
            'header_style',
            [
                'label' => __('Header Style', 'ead-rio'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'header_background',
                'label' => __('Background', 'ead-rio'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .site-header',
                'fields_options' => [
                    'background' => [
                        'default' => 'classic',
                    ],
                    'color' => [
                        'default' => 'rgba(255, 255, 255, 0.95)',
                    ],
                ],
            ]
        );

        $this->add_control(
            'header_padding',
            [
                'label' => __('Padding', 'ead-rio'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default' => [
                    'top' => '20',
                    'right' => '24',
                    'bottom' => '20',
                    'left' => '24',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .site-header__container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'header_border',
                'label' => __('Border', 'ead-rio'),
                'selector' => '{{WRAPPER}} .site-header',
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'header_shadow',
                'label' => __('Box Shadow', 'ead-rio'),
                'selector' => '{{WRAPPER}} .site-header',
                'fields_options' => [
                    'box_shadow_type' => [
                        'default' => 'yes',
                    ],
                    'box_shadow' => [
                        'default' => [
                            'horizontal' => 0,
                            'vertical' => 2,
                            'blur' => 20,
                            'spread' => 0,
                            'color' => 'rgba(0, 0, 0, 0.1)',
                        ],
                    ],
                ],
            ]
        );

        $this->end_controls_section();

        // Logo Style
        $this->start_controls_section(
            'logo_style',
            [
                'label' => __('Logo & Title Style', 'ead-rio'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_logo' => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'site_title_typography',
                'label' => __('Site Title Typography', 'ead-rio'),
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
                ],
                'selector' => '{{WRAPPER}} .site-header__branding .site-title',
                'condition' => [
                    'show_site_title' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'site_title_color',
            [
                'label' => __('Site Title Color', 'ead-rio'),
                'type' => Controls_Manager::COLOR,
                'global' => [
                    'default' => Global_Colors::COLOR_PRIMARY,
                ],
                'selectors' => [
                    '{{WRAPPER}} .site-header__branding .site-title a' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'show_site_title' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        // Navigation Style
        $this->start_controls_section(
            'navigation_style',
            [
                'label' => __('Navigation Style', 'ead-rio'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'menu_typography',
                'label' => __('Menu Typography', 'ead-rio'),
                'global' => [
                    'default' => Global_Typography::TYPOGRAPHY_TEXT,
                ],
                'selector' => '{{WRAPPER}} .site-header__navigation .nav-menu a',
            ]
        );

        $this->add_control(
            'menu_item_color',
            [
                'label' => __('Menu Item Color', 'ead-rio'),
                'type' => Controls_Manager::COLOR,
                'default' => 'rgba(0, 0, 0, 0.87)',
                'selectors' => [
                    '{{WRAPPER}} .site-header__navigation .nav-menu a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'menu_item_hover_color',
            [
                'label' => __('Menu Item Hover Color', 'ead-rio'),
                'type' => Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .site-header__navigation .nav-menu a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // CTA Style
        $this->start_controls_section(
            'cta_style',
            [
                'label' => __('CTA Button Style', 'ead-rio'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_cta' => 'yes',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'cta_typography',
                'label' => __('CTA Typography', 'ead-rio'),
                'selector' => '{{WRAPPER}} .site-header__cta .btn-primary',
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'cta_background',
                'label' => __('CTA Background', 'ead-rio'),
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .site-header__cta .btn-primary',
                'fields_options' => [
                    'background' => [
                        'default' => 'gradient',
                    ],
                ],
            ]
        );

        $this->end_controls_section();
    }

    private function get_available_menus() {
        $menus = wp_get_nav_menus();
        $options = [];

        foreach ($menus as $menu) {
            $options[$menu->term_id] = $menu->name;
        }

        // Add registered menu locations
        $locations = get_registered_nav_menus();
        foreach ($locations as $location => $description) {
            $options[$location] = $description;
        }

        return $options;
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        // Enqueue widget styles
        wp_enqueue_style('site-header-widget');
        wp_enqueue_script('site-header-widget');

        include get_template_directory() . '/widgets/site-header/site-header.template.php';
    }

    protected function content_template() {
        ?>
        <#
        var logoUrl = settings.custom_logo.url || '';
        var siteTitle = settings.custom_site_title || '<?php echo esc_js(get_bloginfo('name')); ?>';
        var ctaText = settings.cta_text || 'Get Started';
        #>

        <div class="site-header site-header-widget">
            <div class="site-header__container">

                <# if (settings.show_logo === 'yes' || settings.show_site_title === 'yes') { #>
                <div class="site-header__branding">
                    <# if (settings.show_logo === 'yes' && logoUrl) { #>
                    <div class="custom-logo-link">
                        <img src="{{ logoUrl }}" alt="{{ siteTitle }}" style="height: {{ settings.logo_size.size }}px;">
                    </div>
                    <# } #>

                    <# if (settings.show_site_title === 'yes') { #>
                    <h1 class="site-title">
                        <a href="#">{{ siteTitle }}</a>
                    </h1>
                    <# } #>

                    <# if (settings.show_tagline === 'yes') { #>
                    <p class="site-description"><?php echo esc_js(get_bloginfo('description')); ?></p>
                    <# } #>
                </div>
                <# } #>

                <nav class="site-header__navigation">
                    <ul class="nav-menu">
                        <li><a href="#">Home</a></li>
                        <li><a href="#">About</a></li>
                        <li><a href="#">Services</a></li>
                        <li><a href="#">Contact</a></li>
                    </ul>
                </nav>

                <# if (settings.show_cta === 'yes') { #>
                <div class="site-header__cta">
                    <a href="{{ settings.cta_link.url }}" class="btn-primary">{{ ctaText }}</a>
                </div>
                <# } #>

                <# if (settings.show_mobile_menu === 'yes') { #>
                <button class="site-header__menu-toggle">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
                <# } #>

            </div>
        </div>
        <?php
    }
}
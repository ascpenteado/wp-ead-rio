/**
 * Site Header Widget JavaScript
 *
 * @package EAD_Rio
 */

(function($) {
    'use strict';

    class SiteHeaderWidget {
        constructor() {
            this.init();
        }

        init() {
            this.bindEvents();
            this.handleScrollEffect();
        }

        bindEvents() {
            // Mobile menu toggle
            $(document).on('click', '.site-header-widget .site-header__menu-toggle', this.toggleMobileMenu);

            // Close mobile menu when clicking outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.site-header-widget').length) {
                    $('.site-header-widget .site-header__navigation').removeClass('active');
                    $('.site-header-widget .site-header__menu-toggle').removeClass('active');
                    $('body').removeClass('menu-open');
                }
            });

            // Close mobile menu when clicking on a link
            $(document).on('click', '.site-header-widget .nav-menu a', function() {
                if (window.innerWidth <= 768) {
                    $('.site-header-widget .site-header__navigation').removeClass('active');
                    $('.site-header-widget .site-header__menu-toggle').removeClass('active');
                    $('body').removeClass('menu-open');
                }
            });

            // Handle window resize
            $(window).on('resize', this.handleResize);

            // Handle scroll for sticky headers
            $(window).on('scroll', this.handleScroll);
        }

        toggleMobileMenu(e) {
            e.preventDefault();

            const $toggle = $(this);
            const $navigation = $toggle.siblings('.site-header__navigation');

            $toggle.toggleClass('active');
            $navigation.toggleClass('active');
            $('body').toggleClass('menu-open');

            // Animate menu items
            if ($navigation.hasClass('active')) {
                $navigation.find('.nav-menu li').each(function(index) {
                    $(this).css('animation-delay', (index * 0.1) + 's');
                });
            }
        }

        handleResize() {
            if (window.innerWidth > 768) {
                $('.site-header-widget .site-header__navigation').removeClass('active');
                $('.site-header-widget .site-header__menu-toggle').removeClass('active');
                $('body').removeClass('menu-open');
            }
        }

        handleScroll() {
            const scrollTop = $(window).scrollTop();
            const $headers = $('.site-header-widget');

            $headers.each(function() {
                const $header = $(this);

                // Add/remove scrolled class for styling
                if (scrollTop > 50) {
                    $header.addClass('scrolled');
                } else {
                    $header.removeClass('scrolled');
                }
            });
        }

        handleScrollEffect() {
            // Initialize scroll effect
            this.handleScroll();
        }
    }

    // Initialize when document is ready
    $(document).ready(function() {
        new SiteHeaderWidget();
    });

    // Re-initialize when Elementor preview updates
    $(window).on('elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/site-header.default', function($scope) {
            new SiteHeaderWidget();
        });
    });

})(jQuery);
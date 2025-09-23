/**
 * Site Header Widget TypeScript Implementation
 * Modern TypeScript version of the site header functionality
 */

import { BaseComponent, ComponentOptions } from '../components/base-component';
import {
  querySelector,
  querySelectorAll,
  toggleClass,
  addClass,
  removeClass,
  debounce,
  throttle
} from '../utils/dom-utils';
import { SELECTORS, CSS_CLASSES, isBreakpoint } from '../utils/theme-config';

interface SiteHeaderOptions extends ComponentOptions {
  mobileBreakpoint?: number;
  scrollThreshold?: number;
  animationDelay?: number;
}

export class SiteHeaderWidget extends BaseComponent {
  private mobileMenuToggle: Element | null = null;
  private navigation: Element | null = null;
  private navMenuItems: NodeListOf<Element> | null = null;
  private isMenuOpen = false;

  // Event handlers bound to this instance
  private boundHandlers = {
    toggleMobileMenu: this.handleMobileMenuToggle.bind(this),
    documentClick: this.handleDocumentClick.bind(this),
    windowResize: debounce(this.handleWindowResize.bind(this), 250),
    windowScroll: throttle(this.handleWindowScroll.bind(this), 16), // ~60fps
    menuItemClick: this.handleMenuItemClick.bind(this)
  };

  constructor(element: Element | string, options: SiteHeaderOptions = {}) {
    const defaultOptions: SiteHeaderOptions = {
      mobileBreakpoint: 768,
      scrollThreshold: 50,
      animationDelay: 100,
      ...options
    };

    super(element, defaultOptions);
  }

  protected bindEvents(): void {
    if (!this.element) return;

    // Find child elements
    this.mobileMenuToggle = querySelector(SELECTORS.menuToggle, this.element);
    this.navigation = querySelector(SELECTORS.navigation, this.element);
    this.navMenuItems = querySelectorAll(`${SELECTORS.navMenu} a`, this.element);

    // Bind events
    if (this.mobileMenuToggle) {
      this.mobileMenuToggle.addEventListener('click', this.boundHandlers.toggleMobileMenu);
    }

    document.addEventListener('click', this.boundHandlers.documentClick);
    window.addEventListener('resize', this.boundHandlers.windowResize);
    window.addEventListener('scroll', this.boundHandlers.windowScroll);

    // Bind menu item clicks for mobile
    this.navMenuItems?.forEach(item => {
      item.addEventListener('click', this.boundHandlers.menuItemClick);
    });

    // Initialize scroll effect
    this.handleWindowScroll();
  }

  protected unbindEvents(): void {
    if (this.mobileMenuToggle) {
      this.mobileMenuToggle.removeEventListener('click', this.boundHandlers.toggleMobileMenu);
    }

    document.removeEventListener('click', this.boundHandlers.documentClick);
    window.removeEventListener('resize', this.boundHandlers.windowResize);
    window.removeEventListener('scroll', this.boundHandlers.windowScroll);

    this.navMenuItems?.forEach(item => {
      item.removeEventListener('click', this.boundHandlers.menuItemClick);
    });
  }

  /**
   * Toggle mobile menu
   */
  private handleMobileMenuToggle(event: Event): void {
    event.preventDefault();

    if (!this.mobileMenuToggle || !this.navigation) return;

    this.isMenuOpen = !this.isMenuOpen;

    // Toggle classes
    toggleClass(this.mobileMenuToggle, CSS_CLASSES.active);
    toggleClass(this.navigation, CSS_CLASSES.active);
    toggleClass(document.body, CSS_CLASSES.menuOpen);

    // Update ARIA attributes
    this.mobileMenuToggle.setAttribute('aria-expanded', this.isMenuOpen.toString());

    // Animate menu items if opening
    if (this.isMenuOpen && this.navMenuItems) {
      this.animateMenuItems();
    }
  }

  /**
   * Handle clicks outside the header to close mobile menu
   */
  private handleDocumentClick(event: Event): void {
    const target = event.target as Element;

    if (!this.element || !this.isMenuOpen) return;

    if (!this.element.contains(target)) {
      this.closeMobileMenu();
    }
  }

  /**
   * Handle window resize
   */
  private handleWindowResize(): void {
    if (!isBreakpoint('mobile') && this.isMenuOpen) {
      this.closeMobileMenu();
    }
  }

  /**
   * Handle window scroll for header effects
   */
  private handleWindowScroll(): void {
    if (!this.element) return;

    const scrollTop = window.pageYOffset;
    const threshold = (this.options as SiteHeaderOptions).scrollThreshold || 50;

    if (scrollTop > threshold) {
      addClass(this.element, CSS_CLASSES.scrolled);
    } else {
      removeClass(this.element, CSS_CLASSES.scrolled);
    }
  }

  /**
   * Handle menu item clicks on mobile
   */
  private handleMenuItemClick(): void {
    if (isBreakpoint('mobile') && this.isMenuOpen) {
      // Add small delay to allow navigation to process
      setTimeout(() => {
        this.closeMobileMenu();
      }, 150);
    }
  }

  /**
   * Close mobile menu
   */
  private closeMobileMenu(): void {
    if (!this.isMenuOpen) return;

    this.isMenuOpen = false;

    if (this.mobileMenuToggle) {
      removeClass(this.mobileMenuToggle, CSS_CLASSES.active);
      this.mobileMenuToggle.setAttribute('aria-expanded', 'false');
    }

    if (this.navigation) {
      removeClass(this.navigation, CSS_CLASSES.active);
    }

    removeClass(document.body, CSS_CLASSES.menuOpen);
  }

  /**
   * Animate menu items on mobile menu open
   */
  private animateMenuItems(): void {
    if (!this.navMenuItems) return;

    const delay = (this.options as SiteHeaderOptions).animationDelay || 100;

    this.navMenuItems.forEach((item, index) => {
      const element = item as HTMLElement;
      element.style.animationDelay = `${index * delay}ms`;
    });
  }

  /**
   * Public API methods
   */
  public openMobileMenu(): void {
    if (!this.isMenuOpen) {
      this.handleMobileMenuToggle(new Event('click'));
    }
  }

  public closeMobileMenuPublic(): void {
    this.closeMobileMenu();
  }

  public isMobileMenuOpen(): boolean {
    return this.isMenuOpen;
  }
}

// jQuery bridge for backward compatibility
declare global {
  interface JQuery {
    siteHeaderWidget(options?: SiteHeaderOptions): JQuery;
  }
}

// Auto-initialize for WordPress/Elementor integration
export function initSiteHeaderWidgets(): void {
  const headers = querySelectorAll(SELECTORS.siteHeader);

  headers.forEach(header => {
    new SiteHeaderWidget(header);
  });
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', initSiteHeaderWidgets);

// Re-initialize for Elementor preview
if (window.elementorFrontend) {
  window.elementorFrontend.hooks.addAction(
    'frontend/element_ready/site-header.default',
    ($scope?: JQuery) => {
      if ($scope) {
        const headerElement = $scope.get(0);
        if (headerElement) {
          new SiteHeaderWidget(headerElement);
        }
      }
    }
  );
}
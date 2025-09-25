/**
 * Theme Configuration Utilities
 * Centralized configuration and constants for the EAD Rio theme
 */

interface ThemeConfig {
  colors: {
    primary: string;
    secondary: string;
    secondaryLight: string;
    grayLight: string;
    grayDark: string;
  };
  fonts: {
    headings: string;
    body: string;
  };
  breakpoints: {
    mobile: number;
    tablet: number;
    desktop: number;
  };
}

export const THEME_CONFIG: ThemeConfig = {
  colors: {
    primary: '#710686',
    secondary: '#ffaa06',
    secondaryLight: '#ffcc80',
    grayLight: '#f5f5f5',
    grayDark: '#333333'
  },
  fonts: {
    headings: '"Titillium Web", sans-serif',
    body: '"Roboto", sans-serif'
  },
  breakpoints: {
    mobile: 768,
    tablet: 1024,
    desktop: 1200
  }
};

export const SELECTORS = {
  siteHeader: '.site-header-widget',
  navigation: '.site-header__navigation',
  menuToggle: '.site-header__menu-toggle',
  navMenu: '.nav-menu',
  body: 'body'
} as const;

export const CSS_CLASSES = {
  active: 'active',
  scrolled: 'scrolled',
  menuOpen: 'menu-open',
  loading: 'loading'
} as const;

export const ANIMATION_DURATION = {
  fast: 200,
  normal: 300,
  slow: 500
} as const;

/**
 * Check if current viewport matches breakpoint
 */
export function isBreakpoint(breakpoint: keyof ThemeConfig['breakpoints']): boolean {
  return window.innerWidth <= THEME_CONFIG.breakpoints[breakpoint];
}

/**
 * Get CSS custom property value
 */
export function getCSSCustomProperty(property: string): string {
  return getComputedStyle(document.documentElement)
    .getPropertyValue(`--${property}`)
    .trim();
}

/**
 * Set CSS custom property value
 */
export function setCSSCustomProperty(property: string, value: string): void {
  document.documentElement.style.setProperty(`--${property}`, value);
}
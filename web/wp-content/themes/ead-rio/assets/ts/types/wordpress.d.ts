/**
 * WordPress & Elementor TypeScript Type Definitions
 * Custom type definitions for WordPress frontend and Elementor integration
 */

// Global WordPress frontend types
declare global {
  interface Window {
    elementorFrontend?: ElementorFrontend;
    jQuery: JQueryStatic;
    $: JQueryStatic;
  }

  // WordPress localized script data
  interface WordPressThemeData {
    ajaxurl: string;
    nonce: string;
    theme_url: string;
    [key: string]: string;
  }
}

// Elementor Frontend API types
interface ElementorFrontend {
  hooks: ElementorHooks;
  elements: {
    $window: JQuery;
    $document: JQuery;
    $body: JQuery;
  };
  isEditMode(): boolean;
  isPreviewMode(): boolean;
}

interface ElementorHooks {
  addAction(tag: string, callback: (scope?: JQuery) => void, priority?: number): void;
  addFilter(tag: string, callback: (...args: any[]) => any, priority?: number): void;
  doAction(tag: string, ...args: any[]): void;
  applyFilters(tag: string, value: any, ...args: any[]): any;
}

// WordPress Menu Item type
interface WordPressMenuItem {
  id: number;
  title: string;
  url: string;
  classes: string[];
  target: string;
  children?: WordPressMenuItem[];
}

// Theme configuration
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

export {};
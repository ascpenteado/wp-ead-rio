/**
 * Main Theme Entry Point
 * TypeScript main entry file for EAD Rio theme
 */

import { ready } from './utils/dom-utils';
import '../components/widgets/site-header/site-header';

// Import type definitions
import './types/wordpress';

/**
 * Theme initialization
 */
class EadRioTheme {
  private static instance: EadRioTheme;

  private constructor() {
    this.init();
  }

  /**
   * Get singleton instance
   */
  public static getInstance(): EadRioTheme {
    if (!EadRioTheme.instance) {
      EadRioTheme.instance = new EadRioTheme();
    }
    return EadRioTheme.instance;
  }

  /**
   * Initialize theme
   */
  private init(): void {
    ready(() => {
      this.initComponents();
      this.bindGlobalEvents();
      console.log('EAD Rio Theme initialized with TypeScript 🚀');
    });
  }

  /**
   * Initialize theme components
   */
  private initComponents(): void {
    // Components are auto-initialized via their respective imports
    // Add more component initializations here as needed
  }

  /**
   * Bind global theme events
   */
  private bindGlobalEvents(): void {
    // Add global event listeners here

    // Example: Smooth scrolling for anchor links
    this.initSmoothScrolling();
  }

  /**
   * Initialize smooth scrolling for anchor links
   */
  private initSmoothScrolling(): void {
    const anchorLinks = document.querySelectorAll('a[href^="#"]');

    anchorLinks.forEach(link => {
      link.addEventListener('click', (event) => {
        const href = (link as HTMLAnchorElement).getAttribute('href');

        if (href && href !== '#' && href !== '#primary') {
          event.preventDefault();

          const target = document.querySelector(href);
          if (target) {
            const offsetTop = (target as HTMLElement).offsetTop - 80; // Account for sticky header

            window.scrollTo({
              top: offsetTop,
              behavior: 'smooth'
            });
          }
        }
      });
    });
  }
}

// Initialize theme
EadRioTheme.getInstance();

// Export for external use if needed
export default EadRioTheme;
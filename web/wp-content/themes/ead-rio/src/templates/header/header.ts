/**
 * Header Component
 *
 * Handles mobile menu toggle functionality and header interactions
 */

export class Header {
  private header: HTMLElement | null = null;
  private menuToggle: HTMLElement | null = null;
  private navigation: HTMLElement | null = null;
  private body: HTMLElement | null = null;
  private isMenuOpen: boolean = false;

  constructor() {
    this.init();
  }

  /**
   * Initialize the header component
   */
  private init(): void {
    this.header = document.querySelector('.header');
    this.menuToggle = document.querySelector('.header__menu-toggle');
    this.navigation = document.querySelector('.header__navigation');
    this.body = document.body;

    if (!this.header || !this.menuToggle || !this.navigation) {
      return;
    }

    this.bindEvents();
    this.handleScroll();
  }

  /**
   * Bind event listeners
   */
  private bindEvents(): void {
    if (!this.menuToggle || !this.navigation) return;

    // Menu toggle click
    this.menuToggle.addEventListener('click', this.toggleMenu.bind(this));

    // Close menu on escape key
    document.addEventListener('keydown', this.handleKeyDown.bind(this));

    // Close menu when clicking outside
    document.addEventListener('click', this.handleClickOutside.bind(this));

    // Handle scroll for header effects
    window.addEventListener('scroll', this.handleScroll.bind(this));

    // Close menu on window resize if mobile breakpoint is exceeded
    window.addEventListener('resize', this.handleResize.bind(this));
  }

  /**
   * Toggle mobile menu visibility
   */
  private toggleMenu(): void {
    if (!this.menuToggle || !this.navigation || !this.body) return;

    this.isMenuOpen = !this.isMenuOpen;

    // Update button state
    this.menuToggle.setAttribute('aria-expanded', this.isMenuOpen.toString());
    this.menuToggle.classList.toggle('header__menu-toggle--toggled', this.isMenuOpen);

    // Update navigation state
    this.navigation.classList.toggle('header__navigation--mobile-open', this.isMenuOpen);

    // Legacy support for existing CSS
    this.navigation.classList.toggle('toggled', this.isMenuOpen);

    // Update body state
    this.body.classList.toggle('menu-open', this.isMenuOpen);

    // Manage focus for accessibility
    if (this.isMenuOpen) {
      this.trapFocus();
    } else {
      this.releaseFocus();
    }
  }

  /**
   * Close the mobile menu
   */
  private closeMenu(): void {
    if (!this.isMenuOpen) return;
    this.toggleMenu();
  }

  /**
   * Handle keyboard events
   */
  private handleKeyDown(event: KeyboardEvent): void {
    if (event.key === 'Escape' && this.isMenuOpen) {
      this.closeMenu();
      this.menuToggle?.focus();
    }
  }

  /**
   * Handle click outside menu
   */
  private handleClickOutside(event: Event): void {
    if (!this.isMenuOpen || !this.header) return;

    const target = event.target as HTMLElement;
    if (!this.header.contains(target)) {
      this.closeMenu();
    }
  }

  /**
   * Handle scroll effects
   */
  private handleScroll(): void {
    if (!this.header) return;

    const scrollY = window.scrollY;
    const scrollThreshold = 50;

    if (scrollY > scrollThreshold) {
      this.header.classList.add('header--scrolled');
    } else {
      this.header.classList.remove('header--scrolled');
    }
  }

  /**
   * Handle window resize
   */
  private handleResize(): void {
    const mobileBreakpoint = 1024;

    if (window.innerWidth > mobileBreakpoint && this.isMenuOpen) {
      this.closeMenu();
    }
  }

  /**
   * Trap focus within the mobile menu for accessibility
   */
  private trapFocus(): void {
    if (!this.navigation) return;

    const focusableElements = this.navigation.querySelectorAll(
      'a[href], button, textarea, input[type="text"], input[type="radio"], input[type="checkbox"], select'
    ) as NodeListOf<HTMLElement>;

    if (focusableElements.length === 0) return;

    const firstElement = focusableElements[0];
    const lastElement = focusableElements[focusableElements.length - 1];

    if (!firstElement || !lastElement) return;

    const handleTabKey = (event: KeyboardEvent) => {
      if (event.key !== 'Tab') return;

      if (event.shiftKey) {
        // Shift + Tab
        if (document.activeElement === firstElement) {
          event.preventDefault();
          lastElement.focus();
        }
      } else {
        // Tab
        if (document.activeElement === lastElement) {
          event.preventDefault();
          firstElement.focus();
        }
      }
    };

    document.addEventListener('keydown', handleTabKey);

    // Focus first element
    firstElement.focus();

    // Store the handler to remove it later
    (this.navigation as any)._focusTrapHandler = handleTabKey;
  }

  /**
   * Release focus trap
   */
  private releaseFocus(): void {
    if (!this.navigation) return;

    const handler = (this.navigation as any)._focusTrapHandler;
    if (handler) {
      document.removeEventListener('keydown', handler);
      delete (this.navigation as any)._focusTrapHandler;
    }
  }

  /**
   * Public method to get menu state
   */
  public isMenuOpened(): boolean {
    return this.isMenuOpen;
  }

  /**
   * Public method to programmatically close menu
   */
  public close(): void {
    this.closeMenu();
  }

  /**
   * Public method to programmatically open menu
   */
  public open(): void {
    if (!this.isMenuOpen) {
      this.toggleMenu();
    }
  }

  /**
   * Clean up event listeners
   */
  public destroy(): void {
    if (this.menuToggle) {
      this.menuToggle.removeEventListener('click', this.toggleMenu.bind(this));
    }

    document.removeEventListener('keydown', this.handleKeyDown.bind(this));
    document.removeEventListener('click', this.handleClickOutside.bind(this));
    window.removeEventListener('scroll', this.handleScroll.bind(this));
    window.removeEventListener('resize', this.handleResize.bind(this));

    this.releaseFocus();
  }
}

// Auto-initialize when DOM is ready
export function initHeader(): Header | null {
  const headerElement = document.querySelector('.header');
  if (!headerElement) {
    return null;
  }

  return new Header();
}

// Export for global access
declare global {
  interface Window {
    EadRioHeader?: Header;
  }
}

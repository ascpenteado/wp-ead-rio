/**
 * Button Component TypeScript
 */

export interface ButtonOptions {
  loadingText?: string;
  disableOnClick?: boolean;
  confirmText?: string;
}

export class Button {
  private element: HTMLElement;
  private options: ButtonOptions;
  private originalText: string;
  private isLoading: boolean = false;

  constructor(element: HTMLElement, options: ButtonOptions = {}) {
    this.element = element;
    this.options = {
      loadingText: 'Loading...',
      disableOnClick: false,
      confirmText: '',
      ...options
    };
    this.originalText = this.element.textContent || '';
    this.init();
  }

  private init(): void {
    this.bindEvents();
  }

  private bindEvents(): void {
    if (this.options.confirmText) {
      this.element.addEventListener('click', this.handleConfirmClick.bind(this));
    }

    if (this.options.disableOnClick) {
      this.element.addEventListener('click', this.handleDisableClick.bind(this));
    }
  }

  private handleConfirmClick(event: Event): void {
    if (!confirm(this.options.confirmText)) {
      event.preventDefault();
      event.stopPropagation();
    }
  }

  private handleDisableClick(): void {
    this.setLoading(true);

    // Re-enable after 2 seconds (prevent accidental double clicks)
    setTimeout(() => {
      this.setLoading(false);
    }, 2000);
  }

  public setLoading(loading: boolean): void {
    this.isLoading = loading;

    if (loading) {
      this.element.classList.add('btn--loading');
      this.element.textContent = this.options.loadingText || 'Loading...';
      this.element.setAttribute('disabled', 'disabled');
    } else {
      this.element.classList.remove('btn--loading');
      this.element.textContent = this.originalText;
      this.element.removeAttribute('disabled');
    }
  }

  public setText(text: string): void {
    this.originalText = text;
    if (!this.isLoading) {
      this.element.textContent = text;
    }
  }

  public destroy(): void {
    // Clean up event listeners if needed
    this.element.removeEventListener('click', this.handleConfirmClick);
    this.element.removeEventListener('click', this.handleDisableClick);
  }
}

// Auto-initialize buttons with data attributes
export function initRioButtons(): void {
  const buttons = document.querySelectorAll<HTMLElement>('.btn[data-button-options]');

  buttons.forEach(button => {
    try {
      const optionsData = button.dataset.buttonOptions;
      const options: ButtonOptions = optionsData ? JSON.parse(optionsData) : {};
      new Button(button, options);
    } catch (error) {
      console.warn('Invalid button options JSON:', error);
      new Button(button);
    }
  });
}

// Backward compatibility alias
export const initButtons = initRioButtons;

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', initRioButtons);
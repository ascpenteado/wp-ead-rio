/**
 * Base Component Class
 * Abstract base class for all theme components
 */

import { ready, addEventListener, removeEventListener } from '../utils/dom-utils';

export interface ComponentOptions {
  selector?: string;
  autoInit?: boolean;
  [key: string]: any;
}

export abstract class BaseComponent {
  protected element: Element | null = null;
  protected options: ComponentOptions;
  protected isInitialized = false;

  constructor(element: Element | string, options: ComponentOptions = {}) {
    this.options = { autoInit: true, ...options };

    if (typeof element === 'string') {
      this.element = document.querySelector(element);
    } else {
      this.element = element;
    }

    if (this.options.autoInit) {
      ready(() => this.init());
    }
  }

  /**
   * Initialize the component
   */
  public init(): void {
    if (this.isInitialized || !this.element) {
      return;
    }

    this.bindEvents();
    this.isInitialized = true;
    this.onInit();
  }

  /**
   * Destroy the component
   */
  public destroy(): void {
    if (!this.isInitialized) {
      return;
    }

    this.unbindEvents();
    this.isInitialized = false;
    this.onDestroy();
  }

  /**
   * Check if component is initialized
   */
  public get initialized(): boolean {
    return this.isInitialized;
  }

  /**
   * Get the component element
   */
  public get el(): Element | null {
    return this.element;
  }

  /**
   * Abstract methods to be implemented by subclasses
   */
  protected abstract bindEvents(): void;
  protected abstract unbindEvents(): void;

  /**
   * Lifecycle hooks
   */
  protected onInit(): void {
    // Override in subclasses
  }

  protected onDestroy(): void {
    // Override in subclasses
  }

  /**
   * Helper method to add event listeners
   */
  protected addEventListeners(
    element: Element,
    events: Record<string, EventListener>
  ): void {
    Object.entries(events).forEach(([event, handler]) => {
      addEventListener(element, event, handler);
    });
  }

  /**
   * Helper method to remove event listeners
   */
  protected removeEventListeners(
    element: Element,
    events: Record<string, EventListener>
  ): void {
    Object.entries(events).forEach(([event, handler]) => {
      removeEventListener(element, event, handler);
    });
  }
}
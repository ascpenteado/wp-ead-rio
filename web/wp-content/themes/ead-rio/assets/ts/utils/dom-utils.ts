/**
 * DOM Utility Functions
 * Helper functions for DOM manipulation and event handling
 */

/**
 * Safely query a single element
 */
export function querySelector<T extends Element = Element>(
  selector: string,
  context: Document | Element = document
): T | null {
  return context.querySelector<T>(selector);
}

/**
 * Safely query multiple elements
 */
export function querySelectorAll<T extends Element = Element>(
  selector: string,
  context: Document | Element = document
): NodeListOf<T> {
  return context.querySelectorAll<T>(selector);
}

/**
 * Add event listener with proper typing
 */
export function addEventListener(
  element: Element,
  type: string,
  listener: EventListener,
  options?: boolean | AddEventListenerOptions
): void {
  element.addEventListener(type, listener, options);
}

/**
 * Remove event listener with proper typing
 */
export function removeEventListener(
  element: Element,
  type: string,
  listener: EventListener,
  options?: boolean | EventListenerOptions
): void {
  element.removeEventListener(type, listener, options);
}

/**
 * Toggle CSS class on element
 */
export function toggleClass(element: Element, className: string, force?: boolean): boolean {
  return element.classList.toggle(className, force);
}

/**
 * Add CSS class to element
 */
export function addClass(element: Element, ...classNames: string[]): void {
  element.classList.add(...classNames);
}

/**
 * Remove CSS class from element
 */
export function removeClass(element: Element, ...classNames: string[]): void {
  element.classList.remove(...classNames);
}

/**
 * Check if element has CSS class
 */
export function hasClass(element: Element, className: string): boolean {
  return element.classList.contains(className);
}

/**
 * Wait for DOM content to be loaded
 */
export function ready(callback: () => void): void {
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', callback, { once: true });
  } else {
    callback();
  }
}

/**
 * Debounce function calls
 */
export function debounce<T extends (...args: any[]) => void>(
  func: T,
  wait: number,
  immediate = false
): (...args: Parameters<T>) => void {
  let timeout: ReturnType<typeof setTimeout> | null = null;

  return function executedFunction(...args: Parameters<T>) {
    const later = () => {
      timeout = null;
      if (!immediate) func(...args);
    };

    const callNow = immediate && !timeout;

    if (timeout) clearTimeout(timeout);
    timeout = setTimeout(later, wait);

    if (callNow) func(...args);
  };
}

/**
 * Throttle function calls
 */
export function throttle<T extends (...args: any[]) => void>(
  func: T,
  limit: number
): (...args: Parameters<T>) => void {
  let inThrottle: boolean;

  return function executedFunction(...args: Parameters<T>) {
    if (!inThrottle) {
      func(...args);
      inThrottle = true;
      setTimeout(() => inThrottle = false, limit);
    }
  };
}
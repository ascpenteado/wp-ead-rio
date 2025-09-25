import { BaseComponent } from '../../../src/components/base-component';
import { ready } from '../../../src/utils/dom-utils';

export class CardsModule extends BaseComponent {
    constructor(element: HTMLElement) {
        super(element, { autoInit: false });
        this.init();
    }

    protected bindEvents(): void {
        // Implementation for binding events
    }

    protected unbindEvents(): void {
        // Implementation for unbinding events
    }

    protected override onInit(): void {
        console.log('CardsModule initialized');
    }
}

ready(() => {
    const cardsModules = document.querySelectorAll<HTMLElement>('[data-widget="cards-module"]');
    cardsModules.forEach(element => {
        new CardsModule(element);
    });
});
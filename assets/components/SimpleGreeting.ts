import { LitElement, html, css } from "lit";
import { customElement, property } from "lit/decorators.js";

const tag = "simple-greeting";

@customElement(tag)
export default class SimpleGreeting extends LitElement {
    // Declare reactive properties
    @property({ type: String })
    name?: string = "World";

    static styles = css`
        :host {
            background: var(--primary);
        }
    `;

    // Render the UI as a function of component state
    render() {
        return html`<p>Hello, ${this.name}!</p>`;
    }
}

declare global {
    interface HTMLElementTagNameMap {
        [tag]: SimpleGreeting;
    }
}

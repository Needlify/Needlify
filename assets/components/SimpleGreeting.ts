import { LitElement, html } from "lit";
import { customElement, property } from "lit/decorators.js";
import "../styles/components/SimpleGreeting.scss";

const tag = "simple-greeting";

@customElement(tag)
export class SimpleGreeting extends LitElement {
    // Declare reactive properties
    @property({ type: String })
    name?: string = "World";

    createRenderRoot() {
        this.classList.add(tag);
        return this;
    }

    // Render the UI as a function of component state
    render() {
        return html`<p class="text-xl">Hello, ${this.name}!</p>`;
    }
}

declare global {
    interface HTMLElementTagNameMap {
        [tag]: SimpleGreeting;
    }
}

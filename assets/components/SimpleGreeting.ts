import { css, html, LitElement } from "lit";
import { customElement, property } from "lit/decorators.js";

@customElement("simple-greeting")
export class SimpleGreeting extends LitElement {
    static styles = css`
        :host {
            color: blue;
        }
    `;

    @property({ type: String })
    firstname?: string = "World";

    render() {
        return html`<p>Hello, ${this.firstname}!</p>`;
    }
}

declare global {
    interface HTMLElementTagNameMap {
        "simple-greeting": SimpleGreeting;
    }
}

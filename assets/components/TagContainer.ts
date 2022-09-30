import { css, html, LitElement } from "lit";
import { customElement } from "lit/decorators.js";

const tag = "tag-container";

@customElement(tag)
export default class TagContainer extends LitElement {
    static styles = css`
        :host {
            display: flex;
            column-gap: 10px;
        }
    `;

    render() {
        return html`<slot></slot>`;
    }
}

declare global {
    interface HTMLElementTagNameMap {
        [tag]: TagContainer;
    }
}

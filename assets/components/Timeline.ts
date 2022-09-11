import { LitElement, html } from "lit";
import { customElement } from "lit/decorators.js";
import "../styles/components/Timeline.scss";

const tag = "time-line";

@customElement(tag)
export default class Timeline extends LitElement {
    createRenderRoot() {
        this.classList.add(tag);
        return this;
    }

    protected render() {
        return html`<div class="container">
            <slot></slot>
        </div>`;
    }
}

declare global {
    interface HTMLElementTagNameMap {
        [tag]: Timeline;
    }
}

import { css, html, LitElement } from "lit";
import { customElement, property } from "lit/decorators.js";

const tag = "timeline-thread-content";

@customElement(tag)
export default class TimelineThreadContent extends LitElement {
    static styles = css`
        :host {
            line-height: 24px;
            color: var(--dark-very-soft);
            font-size: 16px;
            font-weight: 450;

            text-overflow: ellipsis;
            overflow: hidden;
            display: -webkit-box !important;
            -webkit-box-orient: vertical;
            white-space: normal;
        }
    `;

    @property({ type: Number })
    lines: number | null = null;

    connectedCallback() {
        super.connectedCallback();
        if (this.lines) {
            this.style.webkitLineClamp = this.lines?.toString();
        }
    }

    render() {
        return html`<slot></slot>`;
    }
}

declare global {
    interface HTMLElementTagNameMap {
        [tag]: TimelineThreadContent;
    }
}

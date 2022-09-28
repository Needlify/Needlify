import { css, html, LitElement } from "lit";
import { customElement, property } from "lit/decorators.js";

const tag = "timeline-publication";

@customElement(tag)
export default class TimelinePublication extends LitElement {
    static styles = css`
        .timeline-element-content {
            background-color: var(--white);
            border: 2px solid var(--light-soft);
            border-radius: 16px;
            padding: 22px;
            line-height: 26px;
            color: var(--dark-soft);
            font-size: 16px;
            font-weight: 450;
        }

        .tags {
            display: flex;
            column-gap: 10px;
            margin-bottom: 14px;
        }
    `;

    @property({ type: String })
    content?: string;

    render() {
        return html`<div class="timeline-element-content">
            <div class="tags">
                <slot name="tags"></slot>
            </div>
            ${this.content}
        </div>`;
    }
}

declare global {
    interface HTMLElementTagNameMap {
        [tag]: TimelinePublication;
    }
}

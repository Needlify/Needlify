import { css, html, LitElement } from "lit";
import { customElement, property } from "lit/decorators.js";
import "./FeatherIcon";

const tag = "timeline-event";

@customElement(tag)
export default class TimelineEvent extends LitElement {
    static styles = css`
        .timeline-element-content {
            line-height: 26px;
            color: var(--dark-soft);
            font-size: 16px;
            font-weight: 450;
        }
    `;

    @property({ type: String })
    content?: string;

    render() {
        return html` <div class="timeline-element-content">${this.content}</div>`;
    }
}

declare global {
    interface HTMLElementTagNameMap {
        [tag]: TimelineEvent;
    }
}

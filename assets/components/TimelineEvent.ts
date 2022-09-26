import { css, html, LitElement } from "lit";
import { customElement, property } from "lit/decorators.js";
import { unsafeHTML } from "lit/directives/unsafe-html.js";
import "./TimeElapsed";

const tag = "timeline-event";

@customElement(tag)
export default class TimelineEvent extends LitElement {
    static styles = css`
        .timeline-element-content {
            line-height: 26px;
            color: var(--dark-soft);
            font-size: 16px;
            font-weight: 450;
            display: flex;
            column-gap: 8px;
        }

        a {
            color: var(--primary);
            text-decoration: none;
            transition: color 0.2s ease-in-out;
        }

        a:hover {
            color: var(--dark-soft);
        }

        .date {
            font-size: 14px;
            color: var(--light-dark);
        }
    `;

    @property({ type: String })
    content?: string;

    @property({ type: Date })
    date?: Date;

    render() {
        return html` <div class="timeline-element-content">
            <span>${unsafeHTML(this.content)}</span>
            <span>•</span>
            <time-elapsed class="date" date="${this.date}">2 days ago</time-elapsed>
        </div>`;
    }
}

declare global {
    interface HTMLElementTagNameMap {
        [tag]: TimelineEvent;
    }
}

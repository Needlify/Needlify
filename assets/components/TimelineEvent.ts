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
            flex-wrap: wrap; /* TODO Pas sûr de ça */
            column-gap: 8px;
        }

        a {
            color: var(--primary);
            text-decoration: none;
            transition: color 200ms ease-in-out;
        }

        a:hover {
            color: var(--dark);
        }

        .date {
            font-size: 14px;
            color: var(--light-dark);
        }
    `;

    @property({ type: String })
    content?: string;

    @property({ type: String })
    date?: string;

    render() {
        return html` <div class="timeline-element-content">
            <span>${unsafeHTML(this.content)}</span>
            <span>•</span>
            <time-elapsed class="date" date="${this.date}" />
        </div>`;
    }
}

declare global {
    interface HTMLElementTagNameMap {
        [tag]: TimelineEvent;
    }
}

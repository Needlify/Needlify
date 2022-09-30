import { css, html, LitElement } from "lit";
import { customElement } from "lit/decorators.js";

const tag = "timeline-thread-title";

@customElement(tag)
export default class TimelineThreadTitle extends LitElement {
    static styles = css`
        h3 {
            margin: 0;
            display: block;
        }

        h3 a {
            color: var(--dark-light);
            text-decoration: none;
            transition: color: 200ms ease-in-out;
        }

        h3 a:hover {
            color: var(--primary);
        }
    `;

    render() {
        return html`<h3>
            <a href="#">
                <slot></slot>
            </a>
        </h3>`;
    }
}

declare global {
    interface HTMLElementTagNameMap {
        [tag]: TimelineThreadTitle;
    }
}

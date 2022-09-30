import { css, html, LitElement } from "lit";
import { customElement, property } from "lit/decorators.js";
import "./TimeElapsed";

const tag = "timeline-publication";

@customElement(tag)
export default class TimelinePublication extends LitElement {
    static styles = css`
        :host {
            background-color: var(--white);
            border: 2px solid var(--light-soft);
            border-radius: 16px;
            padding: 22px;
            display: flex;
            flex-direction: column;
            row-gap: 16px;
        }

        .publishedAt {
            font-size: 14px;
            color: var(--light-dark);
            display: block;
        }

        slot,
        slot[name] {
            display: contents;
        }
    `;

    @property({ type: String })
    date!: string;

    render() {
        return html`
            <slot name="title"></slot>
            <time-elapsed class="publishedAt" date="${this.date}"></time-elapsed>
            <slot name="tags"></slot>
            <slot name="preview"></slot>
        `;
    }
}

declare global {
    interface HTMLElementTagNameMap {
        [tag]: TimelinePublication;
    }
}

import { css, html, LitElement } from "lit";
import { customElement, property } from "lit/decorators.js";
import StyleSlotedElement from "./StyleSlotedElement";
import "./TimeElapsed";

const tag = "timeline-moodline";

@customElement(tag)
export default class TimelineMoodline extends LitElement {
    static get styles() {
        return [
            StyleSlotedElement.styles,
            css`
                :host {
                    background-color: var(--white);
                    border: 2px solid var(--light-soft);
                    border-radius: 16px;
                    padding: 22px;
                    display: flex;
                    flex-direction: column;
                    row-gap: 10px;
                }

                slot[name="tags"] {
                    display: flex;
                    column-gap: 10px;
                }

                slot[name="content"] {
                    line-height: 24px;
                    color: var(--dark-very-soft);
                    font-size: 16px;
                    font-weight: 450;

                    text-overflow: ellipsis;
                    overflow: hidden;
                    display: -webkit-box !important;
                    -webkit-line-clamp: 4;
                    -webkit-box-orient: vertical;
                    white-space: normal;
                }

                .publishedAt {
                    font-size: 14px;
                    color: var(--light-dark);
                    display: block;
                }
            `,
        ];
    }

    @property({ type: String })
    content?: string;

    @property({ type: String })
    date?: string;

    render() {
        return html`
            <slot name="tags"></slot>
            <slot name="content"></slot>
            <time-elapsed class="publishedAt" date="${this.date}"></time-elapsed>
        `;
    }
}

declare global {
    interface HTMLElementTagNameMap {
        [tag]: TimelineMoodline;
    }
}

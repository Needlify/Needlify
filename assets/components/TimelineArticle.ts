import { css, html, LitElement } from "lit";
import { customElement, property } from "lit/decorators.js";
import "./TimeElapsed";

const tag = "timeline-article";

@customElement(tag)
export default class TimelineArticle extends LitElement {
    static get styles() {
        return [
            css`
                :host {
                    background-color: var(--white);
                    border: 2px solid var(--light-soft);
                    border-radius: 16px;
                    padding: 22px;
                    display: flex;
                    flex-direction: column;
                    row-gap: 16px;
                }

                h3 {
                    margin: 0;
                    color: var(--dark-light);
                    display: block;
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

                .description {
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
            `,
        ];
    }

    @property({ type: String })
    mainTitle!: string;

    @property({ type: String })
    date!: string;

    render() {
        return html`
            <h3>${this.mainTitle}</h3>
            <time-elapsed class="publishedAt" date="${this.date}"></time-elapsed>
            <slot name="tags"></slot>
            <slot name="preview"></slot>
        `;
    }
}

declare global {
    interface HTMLElementTagNameMap {
        [tag]: TimelineArticle;
    }
}

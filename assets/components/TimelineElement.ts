import { css, html, LitElement } from "lit";
import { customElement, property } from "lit/decorators.js";
import { ThreadIcon } from "../enum";
import { ThreadTypeVariation } from "../types.d";
import "./Icon";

const tag = "timeline-element-wc";

@customElement(tag)
export default class TimelineElement extends LitElement {
    static styles = css`
        .timeline-element {
            background-color: var(--white);
            border: 2px solid var(--light-soft);
            border-radius: 16px;
            position: relative;
            padding: 22px;
        }

        .timeline-icon-container {
            position: absolute;
            top: 0;
            left: -50px;
            width: 30px;
            aspect-ratio: 1;
            background-color: var(--primary);
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            border-top: 5px solid var(--light);
            border-bottom: 5px solid var(--light);
        }

        .timeline-icon {
            stroke: cyan;
        }

        .timeline-element-content {
            line-height: 26px;
            color: var(--dark-soft);
            font-size: 16px;
            font-weight: 450;
        }
    `;

    @property({ type: String })
    content?: string;

    @property({ type: String })
    type: ThreadTypeVariation = "article";

    render() {
        return html`<div class="timeline-element">
            <div class="timeline-icon-container">
                <icon-wc icon="${ThreadIcon[this.type].icon}" color="#ebeffd" strokeWidth="2.5px" size="18px">
            </div>
            <div class="timeline-element-content">
                ${this.content}
            </div>
        </div>`;
    }
}

declare global {
    interface HTMLElementTagNameMap {
        [tag]: TimelineElement;
    }
}

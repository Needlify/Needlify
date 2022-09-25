import { css, html, LitElement } from "lit";
import { customElement, property } from "lit/decorators.js";
import { ThreadIcon } from "../enum";
import { ThreadTypeVariation } from "../types.d";
import "./FeatherIcon";

const tag = "timeline-thread";

@customElement(tag)
export default class TimelineThread extends LitElement {
    static styles = css`
        :host {
            position: relative;
        }

        :host(:not(.last))::before {
            content: "";
            position: absolute;
            width: 3px;
            height: calc(100% + 30px);
            top: 0px;
            left: -36px;
            background-color: var(--light-medium);
        }

        .timeline-icon-container {
            position: absolute;
            top: -6px; /* Car border-top */
            left: -48px;
            width: 26px;
            aspect-ratio: 1;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            border-top: 6px solid var(--light);
            border-bottom: 6px solid var(--light);
        }
    `;

    @property({ type: String })
    type: ThreadTypeVariation = "article";

    render() {
        return html`<div class="timeline-element">
            <div class="timeline-icon-container" style="background-color: ${ThreadIcon[this.type].color}">
                <feather-icon icon="${ThreadIcon[this.type].icon}" color="#ebeffd" strokeWidth="2.5px" size="16px">
            </div>
            <slot />
        </div>`;
    }
}

declare global {
    interface HTMLElementTagNameMap {
        [tag]: TimelineThread;
    }
}

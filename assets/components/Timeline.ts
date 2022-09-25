import { css, html, LitElement } from "lit";
import { customElement } from "lit/decorators.js";

const tag = "timeline-wc";

@customElement(tag)
export default class Timeline extends LitElement {
    static styles = css`
        #timeline {
            width: calc(100% - 50px);
            padding-left: 50px;
            position: relative;
            display: flex;
            flex-direction: column;
            row-gap: 15px;
        }

        #timeline::before {
            content: "";
            position: absolute;
            width: 3px;
            height: 100%;
            top: 2px;
            left: 15px;
            background-color: var(--light-medium);
        }
    `;

    render() {
        return html`<div id="timeline">
            <slot />
        </div>`;
    }
}

declare global {
    interface HTMLElementTagNameMap {
        [tag]: Timeline;
    }
}

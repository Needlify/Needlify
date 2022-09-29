import { css, html, LitElement } from "lit";
import { customElement } from "lit/decorators.js";

const tag = "timeline-wrapper";

@customElement(tag)
export default class TimelineWrapper extends LitElement {
    static styles = css`
        #timeline {
            width: calc(100% - 50px);
            padding-left: 50px;
            position: relative;
            display: flex;
            flex-direction: column;
            row-gap: 30px;
        }
    `;

    /**
     * On ajout la classe "last" au dernier élément pour ne pas ajouter le trait de la timeline
     */
    connectedCallback() {
        super.connectedCallback();
        this.shadowRoot?.host.querySelector("timeline-thread:last-child")?.classList.add("last");
    }

    render() {
        return html`<div id="timeline">
            <slot></slot>
        </div>`;
    }
}

declare global {
    interface HTMLElementTagNameMap {
        [tag]: TimelineWrapper;
    }
}

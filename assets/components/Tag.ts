import { css, html, LitElement } from "lit";
import { customElement, property } from "lit/decorators.js";
import "./FeatherIcon";

const tag = "x-tag";

@customElement(tag)
export default class Tag extends LitElement {
    @property({ type: String })
    name?: string;

    @property({ type: String })
    url = "#";

    static styles = css`
        .tag {
            display: inline-flex;
            align-items: center;
            color: var(--dark-soft);
            column-gap: 4px;
            border: 2px solid var(--light-soft);
            border-radius: 6px;
            padding: 4px 6px;
            text-decoration: none;
            transition: background-color 200ms ease-in-out;
        }

        .tag:hover {
            background-color: var(--light);
        }

        span {
            padding: 0;
            margin: 0;
            font-size: 14px;
            font-weight: 600;
            line-height: 0px;
        }
    `;

    render() {
        return html`
            <a class="tag" href="${this.url}">
                <feather-icon icon="hash" color="#a8a8a8" strokeWidth="2.5px" size="14px"></feather-icon>
                <span>${this.name}</span>
            </a>
        `;
    }
}

declare global {
    interface HTMLElementTagNameMap {
        [tag]: Tag;
    }
}

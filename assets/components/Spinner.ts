/* eslint-disable no-unused-vars */
import { css, html, LitElement } from "lit";
import { customElement, property } from "lit/decorators.js";
import "./FeatherIcon";

const tag = "x-spinner";

@customElement(tag)
export default class Spinner extends LitElement {
    @property({ type: String })
    state = "running";

    @property({ type: String })
    width = "22px";

    @property({ type: String })
    height = "22px";

    @property({ type: String })
    color = "black";

    @property({ type: String })
    borderWidth = "3px";

    static styles = css`
        .spinner-container {
            display: inline-block;
            vertical-align: middle;
            border-right-color: transparent !important;
            border-radius: 50%;
            animation: spinner-border 0.75s linear infinite;
        }

        .spinner-container.running {
            animation-play-state: running;
        }
        .spinner-container.paused {
            animation-play-state: paused;
        }

        @keyframes spinner-border {
            to {
                transform: rotate(360deg);
            }
        }
    `;

    render() {
        return html`
            <div
                class="spinner-container ${this.state}"
                style="width: ${this.width}; height: ${this.height}; border: ${this.borderWidth} solid ${this.color};"
            >
            </div
        `;
    }
}

declare global {
    interface HTMLElementTagNameMap {
        [tag]: Spinner;
    }
}

import { html, LitElement } from "lit";
import { customElement, property } from "lit/decorators.js";
import { unsafeHTML } from "lit/directives/unsafe-html.js";
import * as feather from "feather-icons";

const tag = "feather-icon";

@customElement(tag)
export default class FeatherIcon extends LitElement {
    @property({ type: String })
    icon = "user";

    @property({ type: String })
    class = "";

    @property({ type: String })
    color = "black";

    @property({ type: String })
    size = "12px";

    @property({ type: String })
    strokeWidth = "2px";

    render() {
        return html`
            <div class="icon-container" style="width: ${this.size}; height: ${this.size}">
                ${unsafeHTML(
                    feather.icons[this.icon].toSvg({ "class": this.class, "color": this.color, "width": this.size, "height": this.size, "stroke-width": this.strokeWidth }),
                )}
            </div>
        `;
    }
}

declare global {
    interface HTMLElementTagNameMap {
        [tag]: FeatherIcon;
    }
}

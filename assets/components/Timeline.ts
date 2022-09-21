import { LitElement } from "lit";
import { customElement } from "lit/decorators.js";

const tag = "time-line";

@customElement(tag)
export default class Timeline extends LitElement {}

declare global {
    interface HTMLElementTagNameMap {
        [tag]: Timeline;
    }
}

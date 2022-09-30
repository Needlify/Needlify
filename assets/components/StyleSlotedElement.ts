import { css } from "lit";

export default class {
    static styles = css`
        /* Important pour ne pas prendre en compte les slots vides */
        slot:empty,
        slot[name]:empty {
            display: contents !important;
        }
    `;
}

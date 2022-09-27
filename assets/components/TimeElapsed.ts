import { html, LitElement } from "lit";
import { customElement, property } from "lit/decorators.js";
import moment from "moment-timezone";

const tag = "time-elapsed";

@customElement(tag)
export default class TimeElapsed extends LitElement {
    @property({ type: Date })
    date = Date.now();

    constructor() {
        super();
        moment.locale(Intl.DateTimeFormat().resolvedOptions().locale);
    }

    get publishedAtWithTimezone() {
        const publishedAtWithUserTimezone = moment.tz(this.date, Intl.DateTimeFormat().resolvedOptions().timeZone);
        return publishedAtWithUserTimezone.format("LLL");
    }

    getDateDiff(): string {
        const publishedAt = moment.tz(this.date, "UTC");
        return moment(publishedAt).fromNow();
    }

    render() {
        return html`<span title="${this.publishedAtWithTimezone}">${this.getDateDiff()}</span>`;
    }
}

declare global {
    interface HTMLElementTagNameMap {
        [tag]: TimeElapsed;
    }
}

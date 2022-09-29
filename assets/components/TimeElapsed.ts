import { html, LitElement } from "lit";
import { customElement, property } from "lit/decorators.js";
import { DateTime, Interval } from "luxon";

const tag = "time-elapsed";

@customElement(tag)
export default class TimeElapsed extends LitElement {
    /**
     * Date of the publication in ISO-8601 format
     */
    @property({ type: String })
    date: string = DateTime.now().toISO();

    get publishedAtWithTimezone(): string {
        return DateTime.fromISO(this.date.toString()).toLocal().toLocaleString(DateTime.DATETIME_MED);
    }

    connectedCallback(): void {
        super.connectedCallback();
        console.log(this.date);
    }

    getDateDiff(): string | null {
        const publishedAt = DateTime.fromISO(this.date.toString()).setZone("utc");
        const now = DateTime.utc();

        return DateTime.now()
            .toLocal()
            .minus({ seconds: Interval.fromDateTimes(publishedAt, now).length("seconds") })
            .toRelative();
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

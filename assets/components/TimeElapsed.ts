/* eslint-disable no-unused-vars */
import { css, html, LitElement } from "lit";
import { customElement, property } from "lit/decorators.js";
import { DateTime, Interval } from "luxon";

const tag = "time-elapsed";

@customElement(tag)
export default class TimeElapsed extends LitElement {
    static styles = css`
        div {
            display: inline-block;
        }
    `;

    /**
     * Date of the publication in ISO-8601 format
     */
    @property({ type: String })
    date: string = DateTime.now().toISO();

    get publishedAtWithTimezone(): string {
        return DateTime.fromISO(this.date.toString()).toLocal().toLocaleString(DateTime.DATETIME_MED);
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
        return html`<div title="${this.publishedAtWithTimezone}">${this.getDateDiff()}</div>`;
    }
}

declare global {
    interface HTMLElementTagNameMap {
        [tag]: TimeElapsed;
    }
}

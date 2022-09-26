import { html, LitElement } from "lit";
import { customElement, property } from "lit/decorators.js";
import moment from "moment";

const tag = "time-elapsed";

@customElement(tag)
export default class TimeElapsed extends LitElement {
    @property({ type: Date })
    date = Date.now();

    private frenchTimeAssoc = {
        seconds: {
            singulier: "seconde",
            pluriel: "secondes",
        },
        minutes: {
            singulier: "minute",
            pluriel: "minutes",
        },
        hours: {
            singulier: "heure",
            pluriel: "heures",
        },
        days: {
            singulier: "jour",
            pluriel: "jours",
        },
        weeks: {
            singulier: "semaine",
            pluriel: "semaines",
        },
        months: {
            singulier: "mois",
            pluriel: "mois",
        },
        years: {
            singulier: "an",
            pluriel: "ans",
        },
    } as any;

    getDateDiff(): string {
        const publishedAt = moment(this.date);
        const now = moment(Date.now());

        const elapsed = {
            seconds: now.diff(publishedAt, "seconds"),
            minutes: now.diff(publishedAt, "minutes"),
            hours: now.diff(publishedAt, "hours"),
            days: now.diff(publishedAt, "days"),
            weeks: now.diff(publishedAt, "weeks"),
            months: now.diff(publishedAt, "months"),
            years: now.diff(publishedAt, "years"),
        };

        let minValue = Infinity;
        let timeWord = "days";
        Object.entries(elapsed).forEach(([key, value]) => {
            if (!minValue || (value < minValue && value !== 0)) {
                minValue = value;
                timeWord = key;
            }
        });

        return minValue === 1 ? `il y a ${minValue} ${this.frenchTimeAssoc[timeWord].singulier}` : `il y a ${minValue} ${this.frenchTimeAssoc[timeWord].pluriel}`;
    }

    connectedCallback(): void {
        super.connectedCallback();
        this.getDateDiff();
    }

    render() {
        return html`${this.getDateDiff()}`;
    }
}

declare global {
    interface HTMLElementTagNameMap {
        [tag]: TimeElapsed;
    }
}

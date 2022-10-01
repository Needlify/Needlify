import axios from "axios";
import { css, html, LitElement, nothing } from "lit";
import { map } from "lit/directives/map.js";
import { when } from "lit/directives/when.js";
import { choose } from "lit/directives/choose.js";
import { customElement, property, state } from "lit/decorators.js";
import { ThreadTypeVariationEnum } from "../enum";
import "./Spinner";
import "./TimelineThread";
import "./TimelinePublication";
import "./TimelineEvent";
import "./TimelineThreadContent";
import "./TimelineThreadTitle";
import "./TagContainer";
import "./Tag";

const tag = "timeline-wrapper";

@customElement(tag)
export default class TimelineWrapper extends LitElement {
    static styles = css`
        :host {
            width: calc(100% - 50px);
            padding-left: 50px;
            position: relative;
            display: flex;
            flex-direction: column;
            row-gap: 30px;
        }

        #spinner-container {
            display: flex;
            justify-content: center;
        }
    `;

    _data: Array<object> = [];

    set data(val: Array<object>) {
        const oldVal = this._data;
        this._data = val;
        this.requestUpdate("data", oldVal);
    }

    @property()
    get data() {
        return this._data;
    }

    @state()
    isLoading = false;

    /**
     * On ajout la classe "last" au dernier élément pour ne pas ajouter le trait de la timeline
     */
    connectedCallback() {
        super.connectedCallback();
        this.isLoading = true;
        axios.get("/api/rest/threads").then(({ data }) => {
            this.data = data;
            this.isLoading = false;
        });
    }

    renderPublication(type: string, publishedAt: string, tags: Array<any>, preview: string, title: string) {
        return html`
            <timeline-thread type="${type}">
                <timeline-publication date="${publishedAt}">
                    ${type === ThreadTypeVariationEnum.ARTICLE ? html`<timeline-thread-title slot="title">${title}</timeline-thread-title>` : nothing}
                    ${tags.length > 0
                        ? html` <tag-container slot="tags"> ${tags.forEach((currentTag: any) => html`<x-tag name="${currentTag.name}"></x-tag>`)} </tag-container>`
                        : nothing}

                    <timeline-thread-content slot="preview" ${type === ThreadTypeVariationEnum.ARTICLE ? "lines='4'" : nothing}> ${preview} </timeline-thread-content>
                </timeline-publication>
            </timeline-thread>
        `;
    }

    renderEvent(type: string, publishedAt: string, preview: string) {
        return html`
            <timeline-thread type="${type}">
                <timeline-event date="${publishedAt}" content="${preview}"></timeline-event>
            </timeline-thread>
        `;
    }

    renderLoader() {
        return html`
            <div id="spinner-container">
                <x-spinner color="#8a9399"></x-spinner>
            </div>
        `;
    }

    render() {
        // console.log(this.shadowRoot?.host.querySelector("timeline-thread:last-child"));
        // this.shadowRoot?.host.querySelector("timeline-thread:last-child")?.classList.add("last");
        return html`
            ${map(this.data, (thread: any) =>
                choose(thread.type, [
                    [ThreadTypeVariationEnum.ARTICLE, () => this.renderPublication(thread.type, thread.publishedAt, thread.tags, thread.preview, thread.title)],
                    [ThreadTypeVariationEnum.MOODLINE, () => this.renderPublication(thread.type, thread.publishedAt, thread.tags, thread.preview, thread.title)],
                    [ThreadTypeVariationEnum.EVENT, () => this.renderEvent(thread.type, thread.publishedAt, thread.preview)],
                ]),
            )}
            ${when(
                this.isLoading,
                () => this.renderLoader(),
                () => nothing,
            )}
        `;
    }
}

declare global {
    interface HTMLElementTagNameMap {
        [tag]: TimelineWrapper;
    }
}

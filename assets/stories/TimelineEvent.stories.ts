import { Meta } from "@storybook/web-components";
import { html } from "lit";
import "../components/TimelineEvent";

type EventProps = {
    content?: string;
    date?: string;
};

// More on default export: https://storybook.js.org/docs/web-components/writing-stories/introduction#default-export
// https://storybook.js.org/docs/web-components/writing-docs/doc-block-argstable
export default {
    title: "Timeline",
    component: "timeline-event",
    argTypes: {
        content: {
            defaultValue: "Le topic <a href='#'>topic1</a> a été créé",
            description: "The content that will be displayed",
            control: {
                type: "text",
            },
        },
        date: {
            defaultValue: "2022-09-28T22:11:30+00:00",
            description: "Event date",
            control: {
                type: "text",
            },
        },
    },
} as Meta;

export const Event = (args: EventProps) => html`<timeline-event content="${args.content}" date="${args.date}"></timeline-event>`;

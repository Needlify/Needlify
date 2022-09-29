import { Meta } from "@storybook/web-components";
import { html } from "lit";
import "../components/TimeElapsed";

type TimeElapsedProps = {
    date?: string;
};

// More on default export: https://storybook.js.org/docs/web-components/writing-stories/introduction#default-export
// https://storybook.js.org/docs/web-components/writing-docs/doc-block-argstable
export default {
    title: "Utilitary",
    component: "time-elapsed",
    argTypes: {
        date: {
            defaultValue: "1970-01-01T00:00:00+00:00",
            description: "Reference date to calculate the relative time",
            control: {
                type: "text",
            },
        },
    },
} as Meta;

export const TimeElapsed = (args: TimeElapsedProps) => html`<time-elapsed date="${args.date}"></time-elapsed>`;

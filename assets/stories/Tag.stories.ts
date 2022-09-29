import { Meta } from "@storybook/web-components";
import { html } from "lit";
import "../components/Tag";

type TagProps = {
    name?: string;
    url?: string;
};

// More on default export: https://storybook.js.org/docs/web-components/writing-stories/introduction#default-export
// https://storybook.js.org/docs/web-components/writing-docs/doc-block-argstable
export default {
    title: "Utilitary",
    component: "x-icon",
    argTypes: {
        name: {
            defaultValue: "example",
            description: "Name of the hashtag that will be displayed",
            control: {
                type: "text",
            },
        },
        url: {
            defaultValue: "#",
            description: "Corresponding tag url",
            control: {
                type: "text ",
            },
        },
    },
} as Meta;

export const Tag = (args: TagProps) => html`<x-tag name="${args.name}" url="${args.url}"></x-tag>`;

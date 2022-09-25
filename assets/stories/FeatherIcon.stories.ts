import { Meta } from "@storybook/web-components";
import { html } from "lit";
import "../components/FeatherIcon";

type FeatherIconProps = {
    icon?: string;
    class?: string;
    color?: string;
    size?: string;
    strokeWidth?: string;
};

// More on default export: https://storybook.js.org/docs/web-components/writing-stories/introduction#default-export
// https://storybook.js.org/docs/web-components/writing-docs/doc-block-argstable
export default {
    title: "Global",
    component: "feather-icon",
    argTypes: {
        icon: {
            defaultValue: "user",
            description: "Feather icon that will be displayed",
            control: {
                type: "text",
            },
        },
        class: {
            defaultValue: "",
            description: "Custom class",
            control: {
                type: "text",
            },
        },
        color: {
            defaultValue: "black",
            description: "Icon color",
            control: {
                type: "color",
            },
        },
        size: {
            defaultValue: "12px",
            description: "Size of the icon",
            control: {
                type: "text",
            },
        },
        strokeWidth: {
            defaultValue: "2px",
            description: "Icon stroke width",
            control: {
                type: "text",
            },
        },
    },
} as Meta;

export const Icon = (args: FeatherIconProps) =>
    html`<feather-icon icon="${args.icon}" class="${args.class}" color="${args.color}" size="${args.size}" strokeWidth="${args.strokeWidth}"></feather-icon>`;

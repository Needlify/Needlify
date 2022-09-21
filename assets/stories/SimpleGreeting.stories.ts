import { Meta } from "@storybook/web-components";
import { html } from "lit";
import "../components/SimpleGreeting";
import type { SimpleGreetingProps } from "../props.d";

// More on default export: https://storybook.js.org/docs/web-components/writing-stories/introduction#default-export
// https://storybook.js.org/docs/web-components/writing-docs/doc-block-argstable
export default {
    title: "Example/SimpleGreeting",
    component: "simple-greeting",
    argTypes: {
        name: {
            defaultValue: "Needlify",
            description: "Name that will displayed in the component",
            // table: {
            //     type: {
            //         summary: "Something short",
            //         detail: "Something really really long",
            //     },
            // },
            control: {
                type: "text",
            },
        },
    },
} as Meta;

export const WithProps = (args: SimpleGreetingProps) => html`<simple-greeting name="${args.name}"></simple-greeting>`;
export const WithoutProps = () => html`<simple-greeting></simple-greeting>`;

// const PropsTemplate = (args: SimpleGreetingProps) => html`<simple-greeting name="${args.name}"></simple-greeting>`;

// export const WithProps = PropsTemplate.bind({});
// WithProps.args = {
//     name: "John",
// };

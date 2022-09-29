import { Meta } from "@storybook/web-components";
import { html } from "lit";
import "../components/TimelinePublication";

type PublicationProps = {
    content?: string;
};

// More on default export: https://storybook.js.org/docs/web-components/writing-stories/introduction#default-export
// https://storybook.js.org/docs/web-components/writing-docs/doc-block-argstable
export default {
    title: "Timeline",
    component: "timeline-publication",
    argTypes: {
        content: {
            defaultValue:
                "Explicabo voluptatem voluptatem ea aut amet. Quis dolore dolorem laudantium eaque rerum. Rem asperiores at nihil qui enim fuga aut. Molestiae non sit harum earum.",
            description: "The content that will be displayed",
            control: {
                type: "text",
            },
        },
    },
} as Meta;

export const Publication = (args: PublicationProps) => html` <timeline-publication content="${args.content}">
    <div slot="tags">
        <x-tag name="example1"></x-tag>
        <x-tag name="example2"></x-tag>
        <x-tag name="example3"></x-tag>
    </div>
</timeline-publication>`;

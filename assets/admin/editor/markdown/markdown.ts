/* eslint-disable @typescript-eslint/no-unused-vars */
/* eslint-disable no-unused-vars */
import EasyMDE from "easymde";
import "easymde/dist/easymde.min.css";
import { ToolbarButton } from "../../../types";
import "./default.scss";

// Because the EasyMDE did a partial release so the 'upload-image' key doesn't exist on the ToolbarButton type
type EasyMDEConfig = EasyMDE.Options & {
    toolbar?: boolean | ReadonlyArray<"|" | ToolbarButton | EasyMDE.ToolbarIcon | EasyMDE.ToolbarDropdownIcon>;
};

const easyMDE = new EasyMDE({
    element: document.querySelector("textarea.field-markdown-editor-textarea") as HTMLElement,
    autoDownloadFontAwesome: false,
    placeholder: "Start writing the next game changing article...",
    unorderedListStyle: "-",
    indentWithTabs: true,
    lineNumbers: false,
    lineWrapping: true,
    spellChecker: false,
    forceSync: true,
    tabSize: 3,
    toolbar: [
        "undo",
        "redo",
        "|",
        "bold",
        "italic",
        "strikethrough",
        "heading",
        "horizontal-rule",
        "|",
        "quote",
        "code",
        "|",
        "unordered-list",
        "ordered-list",
        "|",
        {
            name: "callout-info",
            action: editor => {
                const cm = editor.codemirror;
                const text = cm.getSelection() || "";
                const output = `> [!info]\n`;
                cm.replaceSelection(output);
            },
            className: "fas fa-circle-info",
            title: "Info callout",
        },
        {
            name: "callout-success",
            action: editor => {
                const cm = editor.codemirror;
                const output = `> [!success]\n`;
                cm.replaceSelection(output);
            },
            className: "fas fa-circle-check",
            title: "Success callout",
        },
        {
            name: "callout-warning",
            action: editor => {
                const cm = editor.codemirror;
                const text = cm.getSelection() || "";
                const output = `> [!warning]\n`;
                cm.replaceSelection(output);
            },
            className: "fas fa-circle-exclamation",
            title: "Alert callout",
        },
        {
            name: "callout-alert",
            action: editor => {
                const cm = editor.codemirror;
                const text = cm.getSelection() || "";
                const output = `> [!alert]\n`;
                cm.replaceSelection(output);
            },
            className: "fas fa-circle-xmark",
            title: "Alert callout",
        },
        "|",
        {
            name: "youtube",
            action: editor => {
                const cm = editor.codemirror;
                const text = cm.getSelection() || "";
                const output = `{youtube:${text}}`;
                cm.replaceSelection(output);
            },
            className: "fas fa-video",
            title: "Embed Youtube video",
        },
        "link",
        "image",
        {
            name: "upload-image",
            action: EasyMDE.drawUploadedImage,
            className: "fas fa-upload",
            title: "Upload image",
        },
        "table",
        "|",
        "fullscreen",
        "preview",
    ],
    toolbarButtonClassPrefix: "mde",
    uploadImage: true,
    imageMaxSize: 1024 * 1024, // 1Mb
    imageAccept: "image/png, image/jpeg, image/gif, image/webp",
    imageUploadEndpoint: "/api/rest/article/image/upload",
} as EasyMDEConfig);

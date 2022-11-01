/* eslint-disable @typescript-eslint/no-unused-vars */
/* eslint-disable no-unused-vars */
import EasyMDE from "easymde";
import "easymde/dist/easymde.min.css";
import "./default.scss";

console.log("Markdown");

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
        {
            name: "checkbox",
            action: editor => {
                const cm = editor.codemirror;
                const text = cm.getSelection() || "";
                const output = `- [ ] ${text}`;
                cm.replaceSelection(output);
            },
            className: "far fa-square-check",
            title: "Checkbox",
        },
        "|",
        "link",
        "image",
        "table",
        "|",
        "fullscreen",
        "preview",
    ],
    toolbarButtonClassPrefix: "mde",
});

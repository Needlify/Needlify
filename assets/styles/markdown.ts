/* eslint-disable no-param-reassign */
/* eslint-disable @typescript-eslint/no-unused-vars */
/* eslint-disable no-unused-vars */
import hljs from "highlight.js";
import "highlight.js/scss/atom-one-dark.scss";
import GLightbox from "glightbox";
import "glightbox/dist/css/glightbox.min.css";
import * as feather from "feather-icons";

import "./markdown.scss";

/* Code highlighting */

// hljs.highlightAll();
hljs.configure({ languages: [] }); // To disable auto-detection
hljs.highlightAll();

/* Wrap images into an anchor tag */

function wrap(el, wrapper) {
    el.parentNode.insertBefore(wrapper, el);
    wrapper.appendChild(el);
}

const images = document.querySelectorAll<HTMLImageElement>(".markdown-style img");

Array.from(images).forEach(img => {
    const a = document.createElement("a");
    a.href = img.src;
    a.classList.add("glightbox");
    wrap(img, a);
});

/* Enable Glightbox */

const lightbox = GLightbox();

/* Wrap tables into divs */

const tables = document.querySelectorAll<HTMLTableElement>(".markdown-style table");

Array.from(tables).forEach(img => {
    const div = document.createElement("div");
    div.classList.add("table-container");
    wrap(img, div);
});

/* Add code blocks language */

const code = document.querySelectorAll<HTMLElement>(".markdown-style pre code.hljs");

Array.from(code).forEach(element => {
    const languages = element.classList.toString().match(/language-\w*/gm);

    if (languages) {
        const actualLang = languages[0].replace("language-", "");
        if (actualLang !== "undefined") {
            element.parentElement?.setAttribute("data-language", actualLang);
        }
    }

    const copy = document.createElement("a");
    copy.innerText = "Copy";
    copy.classList.add("clipboard");
    copy.addEventListener("click", () => {
        navigator.clipboard.writeText(element.innerText);
    });
    element.parentElement?.appendChild(copy);
});

/* Add callout icons */

const callout = document.querySelectorAll<HTMLQuoteElement>("blockquote.callout");

Array.from(callout).forEach(element => {
    const iconElement = document.createElement("i");

    let icon = "";
    if (element.classList.contains("info")) {
        icon = "info";
    } else if (element.classList.contains("success")) {
        icon = "check-circle";
    } else if (element.classList.contains("warning")) {
        icon = "alert-circle";
    } else if (element.classList.contains("alert")) {
        icon = "x-circle";
    }

    iconElement.setAttribute("data-feather", icon);
    iconElement.classList.add("icon");

    element.appendChild(iconElement);
});

feather.replace();
